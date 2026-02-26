<?php

namespace SmsAlert\Response\Message;

class SendBulkMessageResponse
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
     * @return SendBulkMessageResponse
     */
    public function setStatus(bool $status): SendBulkMessageResponse
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
     * @return SendBulkMessageResponse
     */
    public function setCampaignId(int $campaignId): SendBulkMessageResponse
    {
        $this->campaignId = $campaignId;
        return $this;
    }
}
