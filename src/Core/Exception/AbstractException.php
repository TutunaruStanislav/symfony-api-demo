<?php

declare(strict_types=1);

namespace App\Core\Exception;

abstract class AbstractException extends \RuntimeException implements AbstractExceptionInterface
{
    /**
     * @param array<string>|null $outputMessage
     */
    public function __construct(
        string $message = '',
        private readonly string $name = '',
        private readonly string $description = '',
        private readonly ?array $outputMessage = null,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message ?: json_encode($outputMessage, \JSON_UNESCAPED_UNICODE), $code, $previous);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<string>|null
     */
    public function getOutputMessage(): ?array
    {
        return $this->outputMessage;
    }
}
