<?php

namespace SmsAlert\Response;

class GetSmsStatusResponse
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
     * @return GetSmsStatusResponse
     */
    public function setId(string $id): GetSmsStatusResponse
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
     * @return GetSmsStatusResponse
     */
    public function setModemId(string $modemId): GetSmsStatusResponse
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
     * @return GetSmsStatusResponse
     */
    public function setStatus(string $status): GetSmsStatusResponse
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
     * @return GetSmsStatusResponse
     */
    public function setReason(string $reason): GetSmsStatusResponse
    {
        $this->reason = $reason;
        return $this;
    }
}