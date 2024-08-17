<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "App\Entity\User", inversedBy: "userRoles")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "App\Entity\Role", inversedBy: "userRoles")]
    #[ORM\JoinColumn(name: "role_id", referencedColumnName: "id", nullable: false)]
    private $role;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;
        return $this;
    }
}
