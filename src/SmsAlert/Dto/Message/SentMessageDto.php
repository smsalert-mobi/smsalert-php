<?php

declare(strict_types=1);

namespace SmsAlert\Dto\Message;

use SmsAlert\Enum\MessageStatusCodeEnum;

class SentMessageDto
{
    protected string $id;

    protected string $message;

    protected string $to;

    protected MessageStatusCodeEnum $status;

    protected ?string $failReason = null;

    protected \DateTimeInterface $sentAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    public function getStatus(): MessageStatusCodeEnum
    {
        return $this->status;
    }

    public function setStatus(MessageStatusCodeEnum $status): void
    {
        $this->status = $status;
    }

    public function getFailReason(): ?string
    {
        return $this->failReason;
    }

    public function setFailReason(?string $failReason): void
    {
        $this->failReason = $failReason;
    }

    public function getSentAt(): \DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): void
    {
        $this->sentAt = $sentAt;
    }
}
