<?php

declare(strict_types=1);

namespace App\Core\Entity;

use App\Core\Repository\ApiClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiClientRepository::class)]
final class ApiClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => ''])]
    private string $name;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $accessKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAccessKey(): ?string
    {
        return $this->accessKey;
    }

    public function setAccessKey(?string $accessKey): self
    {
        $this->accessKey = $accessKey;

        return $this;
    }
}
