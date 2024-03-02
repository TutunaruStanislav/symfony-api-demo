<?php

declare(strict_types=1);

namespace App\Core\EventListener;

use App\Core\Exception\AbstractExceptionInterface;
use App\Core\Exception\AuthenticationException;
use App\Core\Exception\ValidationException;
use App\Core\Model\ApiResponse;
use Fusonic\HttpKernelBundle\Exception\ConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ExceptionListener
{
    public function __construct(private SerializerInterface $serializer, private LoggerInterface $logger)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $status = $throwable->getCode() ?: Response::HTTP_FORBIDDEN;
        if ($this->isOfValidClass($throwable, [
            ValidationException::class,
            ConstraintViolationException::class,
        ])) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        } elseif ($this->isOfValidClass($throwable, [
            AccessDeniedException::class,
            AuthenticationException::class,
        ])) {
            $status = Response::HTTP_UNAUTHORIZED;
        }

        $response = (new ApiResponse())
            ->setData(null)
            ->setSuccess(false)
            ->setName('Something went wrong')
            ->setMessage('Please try to refresh later')
            ->setTime(date('Y-m-d\TH:i:sO'));

        if ($throwable instanceof AbstractExceptionInterface) {
            $response
                ->setData($throwable->getOutputMessage())
                ->setName($throwable->getName() ?: $response->getName())
                ->setMessage($throwable->getDescription() ?: $response->getMessage());
        }
        if ($this->isOfValidClass($throwable, [
            BadRequestHttpException::class,
            AuthenticationException::class,
            ConstraintViolationException::class,
        ])) {
            $response->setMessage($throwable->getMessage());
        }

        $this->logger->error(sprintf('[request_id]: %s, error: %s',
            $event->getRequest()->attributes->get('requestId'), $this->prepareExceptionMessage($throwable)));

        $event->setResponse(new Response($this->serializer->serialize($response, JsonEncoder::FORMAT), $status, []));
    }

    /**
     * @param array<string> $classNames
     */
    private function isOfValidClass(\Throwable $obj, array $classNames): bool
    {
        foreach ($classNames as $className) {
            if (is_a($obj, $className)) {
                return true;
            }
        }

        return false;
    }

    private function prepareExceptionMessage(\Throwable $throwable): string
    {
        return sprintf('[message]: %s, [file]: %s, [line]: %s',
            $throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
    }
}
