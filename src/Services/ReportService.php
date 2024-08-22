<?php

namespace App\Services;

use App\DTO\Order\OrderCreateDTO;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Report;
use App\Enums\OrderStatusEnum;
use App\Message\GenerateReportMessage;
use App\Repository\CartRepository;
use App\Repository\OrderDeliveryTypeRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ReportService {

    public function __construct(private EntityManagerInterface $entityManager,
        private OrderItemRepository $orderItemRepository,
        private ReportRepository $reportRepository,
        private ParameterBagInterface $parameterBag,
        private MessageBusInterface $bus) {
    }


    public function startGenerate()  {
        $report = new Report();
        $uuid = $report->getId();

        $this->bus->dispatch(new GenerateReportMessage($uuid));

        $this->entityManager->persist($report);
        $this->entityManager->flush();

        return $uuid;
    }

    public function generate($uuid) {
        $report = $this->reportRepository->find($uuid);
        $qb = $this->orderItemRepository->createQueryBuilder('oi');

        $qb->select('p.name as product_name, oi.price, SUM(oi.quantity) as amount, u.id as user_id')
           ->leftJoin('oi.order', 'o')
           ->leftJoin('oi.product', 'p')
           ->leftJoin('o.user', 'u')
           ->groupBy('u.id, p.name, oi.price');

        $query = $qb->getQuery();

        $results = $query->getResult();

        $filePath = $this->writeToFile($results, $uuid);
        $report->setFilePath($filePath);

        $this->entityManager->persist($report);
        $this->entityManager->flush();
    }

    private function writeToFile($results, $uuid): string {
        $reportsDir = $this->parameterBag->get('app.reports_directory');
        $filePath = $reportsDir . '/'. $uuid . '.jsonl';

        if (!is_dir($reportsDir)) {
            if (!mkdir($reportsDir, 0777, true)) {
                throw new \RuntimeException('Не удалось создать директорию для отчетов.');
            }
        }

        $file = fopen($filePath, 'w');

        if ($file === false) {
            throw new \RuntimeException('Не удалось открыть файл для записи.');
        }

        foreach ($results as $result) {
            $line = json_encode([
                'product_name' => $result['product_name'],
                'price' => $result['price'],
                'amount' => $result['amount'],
                'user' => [
                    'id' => $result['user_id']
                ]
            ]);

            fwrite($file, $line . PHP_EOL);
        }

        fclose($file);

        return $filePath;
    }
}