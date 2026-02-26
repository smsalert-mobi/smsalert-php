<?php

namespace SmsAlert;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use SmsAlert\Contract\SmsAlertClientInterface;
use SmsAlert\Dto\Device\DeviceDetailsDto;
use SmsAlert\Dto\Message\PhoneList;
use SmsAlert\Dto\Message\ReceivedMessageDto;
use SmsAlert\Dto\Message\SentMessageDto;
use SmsAlert\Enum\MessageStatusCodeEnum;
use SmsAlert\Exception\InvalidCredentials;
use SmsAlert\Exception\InvalidDate;
use SmsAlert\Exception\InvalidParameters;
use SmsAlert\Response\Device\ListDevicesResponse;
use SmsAlert\Response\Message\GetMessageStatusResponse;
use SmsAlert\Response\Message\ListReceivedMessagesResponse;
use SmsAlert\Response\Message\ListSentMessagesResponse;
use SmsAlert\Response\Message\ScheduleMessageResponse;
use SmsAlert\Response\Message\SendBulkMessageResponse;
use SmsAlert\Response\Message\SendMessageResponse;

class SmsAlertClient implements SmsAlertClientInterface
{
    protected const API_URL = 'https://smsalert.mobi';

    protected const API_VERSION = '/api/v2/';

    protected const SEND_SMS = 'message/send';

    protected const SEND_BULK_SMS = 'message/bulk';

    protected const SCHEDULE_SMS = 'message/schedule';

    protected const CHECK_SMS_STATUS = 'message/getStatus';
    protected const LIST_SENT_MESSAGES = 'message/sent/list';
    protected const LIST_RECEIVED_MESSAGES = 'message/list';
    protected const LIST_DEVICES = 'device/list';


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
     * @return SendMessageResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     */
    public function sendMessage(
        string $phoneNumber,
        string $message,
        bool $cleanupUtf8 = true,
        bool $autoShortUrl = false,
        bool $onlySmsModems = false,
        ?string $modemId = null
    ): SendMessageResponse
    {
        $response = $this->request(self::SEND_SMS, [
            'phoneNumber'   => $phoneNumber,
            'message'       => $message,
            'cleanupUtf8'   => $cleanupUtf8,
            'autoShortUrl'  => $autoShortUrl,
            'onlySmsModems' => $onlySmsModems,
            'modemId'       => $modemId,
        ]);

        $responseEntity = new SendMessageResponse();
        $responseEntity->setSmsCount($response['smsCount']);
        $responseEntity->setId($response['id']);
        $responseEntity->setStatus($response['status']);

        return $responseEntity;
    }

    /**
     * @param PhoneList $phoneList
     * @param string $message
     * @param string|null $date
     * @return SendBulkMessageResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidDate
     * @throws InvalidParameters
     */
    public function sendBulkMessage(PhoneList $phoneList, string $message, ?string $date = null): SendBulkMessageResponse
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

        $responseEntity = new SendBulkMessageResponse();
        $responseEntity->setStatus($response['status']);
        $responseEntity->setCampaignId($response['campaignId']);

        return $responseEntity;
    }

    /**
     * @param string $phoneNumber
     * @param string $message
     * @param string $date
     * @param string|null $modemId
     * @return ScheduleMessageResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidDate
     * @throws InvalidParameters
     */
    public function scheduleMessage(string $phoneNumber, string $message, string $date, ?string $modemId = null): ScheduleMessageResponse
    {
        if (!$this->validateDate($date)) {
            throw new InvalidDate('Date format should be Y-m-d H:i:s');
        }

        $response = $this->request(self::SCHEDULE_SMS, [
            'phoneNumber'  => $phoneNumber,
            'message'      => $message,
            'scheduleDate' => $date,
            'modemId'      => $modemId,
        ]);

        $responseEntity = new ScheduleMessageResponse();
        $responseEntity->setSmsCount($response['smsCount']);
        $responseEntity->setId($response['id']);
        $responseEntity->setStatus($response['status']);

        return $responseEntity;
    }

    /**
     * @param string $id
     * @return GetMessageStatusResponse
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     */
    public function checkMessageStatus(string $id): GetMessageStatusResponse
    {
        $response = $this->request(self::CHECK_SMS_STATUS, [
            'id' => $id,
        ]);

        $responseEntity = new GetMessageStatusResponse();
        $responseEntity->setId($response['id']);
        $responseEntity->setModemId($response['modemId']);
        $responseEntity->setStatus($response['status']);
        $responseEntity->setReason($response['reason'] ?? '');

        return $responseEntity;
    }

    /**
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     * @throws \Exception
     */
    public function listSentMessages(int $page = 1, int $perPage = 50): ListSentMessagesResponse
    {
        $response = $this->request(self::LIST_SENT_MESSAGES, [], [
            'page'   => $page,
            'perPage'   => $perPage,
        ], 'GET');

        $sentMessages = [];

        foreach ($response['data'] as $sentMessage) {
            $sentMessageDto = new SentMessageDto();
            $sentMessageDto->setId($sentMessage['id']);
            $sentMessageDto->setMessage($sentMessage['message']);
            $sentMessageDto->setTo($sentMessage['to']);
            $sentMessageDto->setFailReason($sentMessage['failReason']);
            $sentMessageDto->setSentAt(new DateTime($sentMessage['date']));
            $sentMessageDto->setStatus(MessageStatusCodeEnum::from($sentMessage['status']));

            $sentMessages[] = $sentMessageDto;
        }

        $responseEntity = new ListSentMessagesResponse();
        $responseEntity->setStatus($response['status']);
        $responseEntity->setTotalResults($response['totalResults']);
        $responseEntity->setTotalPages($response['totalPages']);
        $responseEntity->setResultsPerPage($response['resultsPerPage']);
        $responseEntity->setPage($response['page']);
        $responseEntity->setSentMessages($sentMessages);

        return $responseEntity;
    }

    /**
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     * @throws \Exception
     */
    public function listReceivedMessages(int $page = 1, int $perPage = 50): ListReceivedMessagesResponse
    {
        $response = $this->request(self::LIST_RECEIVED_MESSAGES, [], [
            'page'   => $page,
            'perPage'   => $perPage,
        ], 'GET');

        $receivedMessages = [];

        foreach ($response['data'] as $receivedMessage) {
            $receivedMessageDto = new ReceivedMessageDto();
            $receivedMessageDto->setId($receivedMessage['id']);
            $receivedMessageDto->setMessage($receivedMessage['message']);
            $receivedMessageDto->setFrom($receivedMessage['from']);
            $receivedMessageDto->setReceivedAt(new DateTime($receivedMessage['date']));

            $receivedMessages[] = $receivedMessageDto;
        }

        $responseEntity = new ListReceivedMessagesResponse();
        $responseEntity->setStatus($response['status']);
        $responseEntity->setTotalResults($response['totalResults']);
        $responseEntity->setTotalPages($response['totalPages']);
        $responseEntity->setResultsPerPage($response['resultsPerPage']);
        $responseEntity->setPage($response['page']);
        $responseEntity->setSentMessages($receivedMessages);

        return $responseEntity;
    }

    /**
     * @throws InvalidCredentials
     * @throws GuzzleException
     * @throws InvalidParameters
     * @throws \Exception
     */
    public function listDevices(int $page = 1, int $perPage = 50): ListDevicesResponse
    {
        $response = $this->request(self::LIST_DEVICES, [], [
            'page'   => $page,
            'perPage'   => $perPage,
        ], 'GET');

        $devices = [];

        foreach ($response['data'] as $device) {
            if (empty($device['id'])) {
                continue;
            }
            $deviceDetailsDto = new DeviceDetailsDto();
            $deviceDetailsDto->setId($device['id']);
            $deviceDetailsDto->setNickName($device['nickName']);
            $deviceDetailsDto->setLastSeenAt($device['lastSeen'] ? new DateTime($device['lastSeen']) : null);
            $deviceDetailsDto->setIsOnline($device['isOnline']);
            $deviceDetailsDto->setVersion($device['version']);

            $devices[] = $deviceDetailsDto;
        }

        $responseEntity = new ListDevicesResponse();
        $responseEntity->setStatus($response['status']);
        $responseEntity->setTotalResults($response['totalResults']);
        $responseEntity->setTotalPages($response['totalPages']);
        $responseEntity->setResultsPerPage($response['resultsPerPage']);
        $responseEntity->setPage($response['page']);
        $responseEntity->setDevices($devices);

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
     * @param array $body
     * @param array $query
     * @param string $method
     * @return array|null
     * @throws GuzzleException
     * @throws InvalidCredentials
     * @throws InvalidParameters
     */
    private function request(string $uri, array $body, array $query = [], string $method = 'POST'): ?array
    {
        $client = new Client(
            [
                'base_uri' => self::API_URL,
                'auth' => [$this->username, $this->apiKey],
            ]
        );

        try {
            $request = $client->request(
                $method,
                self::API_VERSION . $uri,
                [
                    'json' => $body,
                    'query' => $query,
                ]
            );
            $response = $request->getBody()->getContents();
            return json_decode($response, true);
        } catch (ClientException $e) {

            if ($e->getCode() == 401) {
                throw new InvalidCredentials();
            }

            $error = json_decode($e->getResponse()->getBody(), true);
            throw new InvalidParameters($error['message']);
        }
    }
}
