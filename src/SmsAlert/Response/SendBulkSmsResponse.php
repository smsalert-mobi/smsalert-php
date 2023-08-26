<?php

namespace SmsAlert\Response;

class SendBulkSmsResponse
{

    protected bool $status;

    protected int $campaignId;

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return SendBulkSmsResponse
     */
    public function setStatus(bool $status): SendBulkSmsResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    /**
     * @param int $campaignId
     * @return SendBulkSmsResponse
     */
    public function setCampaignId(int $campaignId): SendBulkSmsResponse
    {
        $this->campaignId = $campaignId;
        return $this;
    }
}