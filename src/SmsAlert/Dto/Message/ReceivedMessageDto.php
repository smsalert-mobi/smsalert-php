<?php

declare(strict_types=1);

namespace SmsAlert\Dto\Message;

class ReceivedMessageDto
{
    protected string $id;

    protected string $message;

    protected string $from;

    protected bool $seen = false;

    protected \DateTimeInterface $receivedAt;

    public function getReceivedAt(): \DateTimeInterface
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(\DateTimeInterface $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
    }

    public function isSeen(): bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): void
    {
        $this->seen = $seen;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
