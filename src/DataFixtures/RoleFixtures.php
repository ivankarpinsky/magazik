<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Enums\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $roles = RoleEnum::values();

        foreach ($roles as $slug) {
            $role = new Role();
            $role->setSlug($slug);
            $manager->persist($role);
        }

        $manager->flush();
    }
}