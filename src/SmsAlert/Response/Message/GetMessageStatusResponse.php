<?php

namespace SmsAlert\Response\Message;

class GetMessageStatusResponse
{
    protected string $id;

    protected string $modemId;

    protected string $status;

    protected string $reason;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return GetMessageStatusResponse
     */
    public function setId(string $id): GetMessageStatusResponse
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getModemId(): string
    {
        return $this->modemId;
    }

    /**
     * @param string $modemId
     * @return GetMessageStatusResponse
     */
    public function setModemId(string $modemId): GetMessageStatusResponse
    {
        $this->modemId = $modemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return GetMessageStatusResponse
     */
    public function setStatus(string $status): GetMessageStatusResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     * @return GetMessageStatusResponse
     */
    public function setReason(string $reason): GetMessageStatusResponse
    {
        $this->reason = $reason;
        return $this;
    }
}
