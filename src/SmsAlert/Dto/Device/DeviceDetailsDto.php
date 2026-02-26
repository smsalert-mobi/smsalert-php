<?php

declare(strict_types=1);

namespace SmsAlert\Dto\Device;

class DeviceDetailsDto
{
    protected string $id;

    protected string $nickName;

    protected ?\DateTimeInterface $lastSeenAt;

    protected bool $isOnline;

    protected ?string $connectionToken;

    protected ?string $version;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNickName(): string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): void
    {
        $this->nickName = $nickName;
    }

    public function getLastSeenAt(): ?\DateTimeInterface
    {
        return $this->lastSeenAt;
    }

    public function setLastSeenAt(?\DateTimeInterface $lastSeenAt): void
    {
        $this->lastSeenAt = $lastSeenAt;
    }

    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): void
    {
        $this->isOnline = $isOnline;
    }

    public function getConnectionToken(): ?string
    {
        return $this->connectionToken;
    }

    public function setConnectionToken(?string $connectionToken): void
    {
        $this->connectionToken = $connectionToken;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): void
    {
        $this->version = $version;
    }
}
