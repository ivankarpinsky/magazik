<?php

namespace App\DataFixtures;

use App\Entity\OrderDeliveryType;
use App\Enums\OrderDeliveryTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderDeliveryTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $deliveryTypes = OrderDeliveryTypeEnum::values();

        foreach ($deliveryTypes as $slug) {
            $type = new OrderDeliveryType();
            $type->setSlug($slug);
            $manager->persist($type);
        }

        $manager->flush();
    }
}