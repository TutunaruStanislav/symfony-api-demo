<?php

namespace App\Core\Exception;

interface AbstractExceptionInterface
{
    public function getName(): string;

    public function getDescription(): string;

    /**
     * @return array<string>|null
     */
    public function getOutputMessage(): ?array;
}
