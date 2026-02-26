<?php

namespace SmsAlert\Contract;

use SmsAlert\Dto\Message\PhoneList;
use SmsAlert\Response\Device\ListDevicesResponse;
use SmsAlert\Response\Message\GetMessageStatusResponse;
use SmsAlert\Response\Message\ListReceivedMessagesResponse;
use SmsAlert\Response\Message\ListSentMessagesResponse;
use SmsAlert\Response\Message\ScheduleMessageResponse;
use SmsAlert\Response\Message\SendBulkMessageResponse;
use SmsAlert\Response\Message\SendMessageResponse;

interface SmsAlertClientInterface
{

    public function sendMessage(
        string $phoneNumber,
        string $message,
        bool $cleanupUtf8 = true,
        bool $autoShortUrl = false,
        bool $onlySmsModems = false,
        ?string $modemId = null
    ): SendMessageResponse;
    public function sendBulkMessage(
        PhoneList $phoneList,
        string $message,
        ?string $date = null
    ): SendBulkMessageResponse;
    public function scheduleMessage(
        string $phoneNumber,
        string $message,
        string $date,
        ?string $modemId = null
    ): ScheduleMessageResponse;
    public function checkMessageStatus(string $id): GetMessageStatusResponse;

    public function listSentMessages(int $page = 1, int $perPage = 50): ListSentMessagesResponse;

    public function listReceivedMessages(int $page = 1, int $perPage = 50): ListReceivedMessagesResponse;

    public function listDevices(int $page = 1, int $perPage = 50): ListDevicesResponse;
}
