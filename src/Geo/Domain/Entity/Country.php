<?php

declare(strict_types=1);

namespace App\Geo\Domain\Entity;

use App\Geo\Domain\Repository\CountryRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepositoryInterface::class)]
#[ORM\Table('geo_country')]
#[ORM\Index(columns: ['code_iso'], name: 'country_code_iso_index')]
final class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 3, options: ['default' => ''])]
    private string $codeIso;

    #[ORM\Column(type: 'string', length: 10, options: ['default' => ''])]
    private string $phoneMask;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $phoneLength;

    #[ORM\Column(type: 'string', length: 3, options: ['default' => ''])]
    private string $codeAlpha;

    #[ORM\Column(type: 'integer', options: ['default' => 999])]
    private int $sortPriority;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCodeIso(): string
    {
        return $this->codeIso;
    }

    public function setCodeIso(string $codeIso): self
    {
        $this->codeIso = $codeIso;

        return $this;
    }

    public function getSortPriority(): int
    {
        return $this->sortPriority;
    }

    public function setSortPriority(int $sortPriority): self
    {
        $this->sortPriority = $sortPriority;

        return $this;
    }

    public function getPhoneMask(): string
    {
        return $this->phoneMask;
    }

    public function setPhoneMask(string $phoneMask): self
    {
        $this->phoneMask = $phoneMask;

        return $this;
    }

    public function getPhoneLength(): int
    {
        return $this->phoneLength;
    }

    public function setPhoneLength(int $phoneLength): self
    {
        $this->phoneLength = $phoneLength;

        return $this;
    }

    public function getCodeAlpha(): string
    {
        return $this->codeAlpha;
    }

    public function setCodeAlpha(string $codeAlpha): self
    {
        $this->codeAlpha = $codeAlpha;

        return $this;
    }
}
