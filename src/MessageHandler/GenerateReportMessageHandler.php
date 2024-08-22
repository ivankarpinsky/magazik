<?php

namespace App\MessageHandler;

use App\Message\GenerateReportMessage;
use App\Services\ReportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GenerateReportMessageHandler
{
    private $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function __invoke(GenerateReportMessage $message)
    {
        $reportId = $message->getReportId();
        $this->reportService->generate($reportId);
    }
}