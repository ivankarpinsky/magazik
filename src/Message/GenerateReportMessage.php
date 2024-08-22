<?php

namespace App\Message;

class GenerateReportMessage
{
    private int $reportId;

    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
    }

    public function getReportId(): int
    {
        return $this->reportId;
    }
}
