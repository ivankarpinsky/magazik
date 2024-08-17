<?php

namespace App\Services;

use App\DTO\User\UserRegisterDTO;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use App\Enums\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService {

    public function __construct(private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher) {}

    public function register(UserRegisterDTO $userRegisterDTO): User {
        $user = new User();
        $user->setName($userRegisterDTO->name);
        $user->setPhone($userRegisterDTO->phone);
        $user->setEmail($userRegisterDTO->email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $userRegisterDTO->password);
        $user->setPassword($hashedPassword);

        // Находим роль "user" в таблице ролей по slug
        $roleRepository = $this->entityManager->getRepository(Role::class);
        $role = $roleRepository->findOneBy(['slug' => RoleEnum::USER]);

        if ($role instanceof Role) {
            $userRole = new UserRole();
            $userRole->setUser($user);
            $userRole->setRole($role);

            $user->addUserRole($userRole);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}