<?php

declare(strict_types=1);

namespace SmsAlert\Response\Device;

use SmsAlert\Dto\Message\SentMessageDto;

class ListDevicesResponse
{
    protected bool $status;

    protected int $totalResults;

    protected int $totalPages;

    protected int $resultsPerPage;

    protected int $page;

    /**
     * @var SentMessageDto[]
     */
    protected array $devices = [];

    public function getDevices(): array
    {
        return $this->devices;
    }

    public function setDevices(array $devices): void
    {
        $this->devices = $devices;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    public function setResultsPerPage(int $resultsPerPage): void
    {
        $this->resultsPerPage = $resultsPerPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): void
    {
        $this->totalPages = $totalPages;
    }

    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function setTotalResults(int $totalResults): void
    {
        $this->totalResults = $totalResults;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
}
