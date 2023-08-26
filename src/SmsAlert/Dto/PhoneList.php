<?php

namespace SmsAlert\Dto;

class PhoneList
{

    protected array $list;

    /**
     * @param string $tel
     *
     * @return $this
     */
    public function addPhoneNumber(string $tel): PhoneList
    {
        $this->list[] = trim($tel);

        return $this;
    }

    public function getPhoneList(): array
    {
        return $this->list;
    }
}