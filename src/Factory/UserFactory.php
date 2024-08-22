<?php

namespace App\Factory;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use App\Enums\RoleEnum;
use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;


/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory {
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */

    public function __construct(private UserPasswordHasherInterface $passwordHasher,
        private RoleRepository $roleRepository) {
    }

    public static function class(): string {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable {
        return [
            'email' => self::faker()->email(),
            'name' => self::faker()->name(),
            'password' => self::faker()->password(),
            'phone' => self::faker()->phoneNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static {
//        return $this;
        $roleRepository = $this->roleRepository;
        $role = $roleRepository->findOneBy(['slug' => RoleEnum::USER]);
        return $this->afterInstantiate(function (User $user) use (
            $role
        ): void {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
        });
    }

    public function withRole($roleSlug): self {
        $role = $this->roleRepository->findOneBy(['slug' => $roleSlug]);

        return $this->afterInstantiate(function(User $user) use ($role) {
            if ($role instanceof Role) {
                $userRole = new UserRole();
                $userRole->setUser($user);
                $userRole->setRole($role);

                $user->addUserRole($userRole);
            }
        });
    }
}
