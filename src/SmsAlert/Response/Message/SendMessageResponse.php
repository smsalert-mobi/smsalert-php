<?php

namespace SmsAlert\Response\Message;

class SendMessageResponse
{
    protected string $id;

    protected int $smsCount;

    protected bool $status;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getSmsCount(): int
    {
        return $this->smsCount;
    }

    /**
     * @param int $smsCount
     */
    public function setSmsCount(int $smsCount): void
    {
        $this->smsCount = $smsCount;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
}
