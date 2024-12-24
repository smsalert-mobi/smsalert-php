<?php

namespace SmsAlert;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use SmsAlert\Dto\MessageList;
use SmsAlert\Exception\InvalidCredentials;
use SmsAlert\Exception\InvalidDate;
use SmsAlert\Exception\InvalidParameters;
use SmsAlert\Dto\PhoneList;
use SmsAlert\Response\GetSmsStatusResponse;
use SmsAlert\Response\ScheduleSmsResponse;
use SmsAlert\Response\SendBulkSmsResponse;
use SmsAlert\Response\SendSmsResponse;

class SmsClient
{

    protected const API_URL = 'https://smsalert.mobi';

    protected const API_VERSION = '/api/v2/';

    protected const SEND_SMS = 'message/send';

    protected const SEND_BULK_SMS = 'message/bulk';

    protected const SCHEDULE_SMS = 'message/schedule';

    protected const CHECK_SMS_STATUS = 'message/getStatus';

    protected const SEND_BULK_CUSTOM_SMS = 'sendBulkCustomSms';

    private string $username;

    private string $apiKey;

    /**
     * @param string $username
     * @param string $apiKey
     */
    public function __construct(string $username, string $apiKey)
    {
        $this->username = $username;
        $this->apiKey   = $apiKey;
    }

    /**
     * @param string $phoneNumber
     * @param string $message
     * @param bool $cleanupUtf8
     * @param bool $autoShortUrl
     * @param bool $onlySmsModems
     * @param string|null $modemId
     * @return SendSmsResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     */
    public function sendSms(
        string $phoneNumber,
        string $message,
        bool $cleanupUtf8 = true,
        bool $autoShortUrl = false,
        bool $onlySmsModems = false,
        ?string $modemId = null
    ): SendSmsResponse
    {
        $response = $this->request(self::SEND_SMS, [
            'phoneNumber'   => $phoneNumber,
            'message'       => $message,
            'cleanupUtf8'   => $cleanupUtf8,
            'autoShortUrl'  => $autoShortUrl,
            'onlySmsModems' => $onlySmsModems,
            'modemId'       => $modemId,
        ]);

        $responseEntity = new SendSmsResponse();
        $responseEntity->setSmsCount($response['smsCount']);
        $responseEntity->setId($response['id']);
        $responseEntity->setStatus($response['status']);

        return $responseEntity;
    }

    /**
     * @param PhoneList $phoneList
     * @param string $message
     * @param string|null $date
     * @return SendBulkSmsResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidDate
     * @throws InvalidParameters
     */
    public function sendBulkSms(PhoneList $phoneList, string $message, ?string $date = null): SendBulkSmsResponse
    {
        $data = [
            'phoneList'   => $phoneList->getPhoneList(),
            'message'     => $message,
        ];

        if (!empty($date) && !$this->validateDate($date)) {
            throw new InvalidDate('Date format should be Y-m-d H:i:s');
        }  else {
            $data['schedule'] = $date;
        }

        $response = $this->request(self::SEND_BULK_SMS, $data);

        $responseEntity = new SendBulkSmsResponse();
        $responseEntity->setStatus($response['status']);
        $responseEntity->setCampaignId($response['campaignId']);

        return $responseEntity;
    }

    /**
     * @param MessageList $messageList
     * @param string|null $date
     * @return array|null
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidDate
     * @throws InvalidParameters
     */
    public function sendCustomBulkSMs(MessageList $messageList, ?string $date = null): ?array
    {
        $data = [
            'messageList' => $messageList->__toString(),
        ];

        if (!empty($date) && !$this->validateDate($date)) {
            throw new InvalidDate('Date format should be Y-m-d H:i:s');
        }  else {
            $data['schedule'] = $date;
        }

        $response = $this->request(self::SEND_BULK_CUSTOM_SMS, $data);

        return $response['ids'];
    }

    /**
     * @param string $tel
     * @param string $message
     * @param string $date
     * @param string|null $modemId
     * @return ScheduleSmsResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidDate
     * @throws InvalidParameters
     */
    public function scheduleSms(string $tel, string $message, string $date, ?string $modemId = null): ScheduleSmsResponse
    {
        if (!$this->validateDate($date)) {
            throw new InvalidDate('Date format should be Y-m-d H:i:s');
        }

        $response = $this->request(self::SCHEDULE_SMS, [
            'phoneNumber'  => $tel,
            'message'      => $message,
            'scheduleDate' => $date,
            'modemId'      => $modemId,
        ]);

        $responseEntity = new ScheduleSmsResponse();
        $responseEntity->setSmsCount($response['smsCount']);
        $responseEntity->setId($response['id']);
        $responseEntity->setStatus($response['status']);

        return $responseEntity;
    }

    /**
     * @param string $id
     * @return GetSmsStatusResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     */
    public function checkSmsStatus(string $id): GetSmsStatusResponse
    {
        $response = $this->request(self::CHECK_SMS_STATUS, [
            'id' => $id,
        ]);

        $responseEntity = new GetSmsStatusResponse();
        $responseEntity->setId($response['id']);
        $responseEntity->setModemId($response['modemId']);
        $responseEntity->setStatus($response['status']);
        $responseEntity->setReason($response['reason'] ?? '');

        return $responseEntity;
    }

    /**
     * @param  string  $date
     *
     * @return bool
     */
    private function validateDate(string $date): bool
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $dateTime && $dateTime->format('Y-m-d H:i:s') == $date;
    }

    /**
     * @param string $uri
     * @param $params
     * @return array|null
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     */
    private function request(string $uri, $params): ?array
    {
        $client = new Client(
            [
                'base_uri' => self::API_URL,
                 'auth'    => [$this->username, $this->apiKey],
            ]
        );

        try {
            $request = $client->request(
                'POST',
                self::API_VERSION . $uri,
                [
                    'json' => $params
                ]
            );
            $response = $request->getBody()->getContents();
            return json_decode($response, true);
        } catch (ClientException $e) {

            if ($e->getCode() == 401) {
                throw new InvalidCredentials();
            }

            $error =  json_decode($e->getResponse()->getBody(), true);
            throw new InvalidParameters($error['message']);
        }
    }
}
