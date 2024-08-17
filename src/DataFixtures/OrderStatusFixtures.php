<?php

namespace App\DataFixtures;

use App\Entity\OrderStatus;
use App\Enums\OrderStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $statuses = OrderStatusEnum::values();

        foreach ($statuses as $slug) {
            $status = new OrderStatus();
            $status->setSlug($slug);
            $manager->persist($status);
        }

        $manager->flush();
    }
}