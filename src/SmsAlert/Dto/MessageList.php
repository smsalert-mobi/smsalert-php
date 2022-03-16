<?php

namespace SmsAlert\Dto;

class MessageList
{

    protected array $list;

    /**
     * @param string $tel
     * @param string $message
     *
     * @return $this
     */
    public function addMessage(string $tel, string $message): MessageList
    {
        $this->list[] = [
          'tel'     => trim($tel),
          'message' => $message,
        ];

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->list);
    }
}