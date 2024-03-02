<?php

declare(strict_types=1);

namespace App\Core\EventListener;

use App\Core\Exception\AbstractExceptionInterface;
use App\Core\Exception\AuthenticationException;
use App\Core\Model\ApiResponse;
use App\Core\Repository\ApiClientRepository;
use Nelmio\ApiDocBundle\Controller\DocumentationController;
use Nelmio\ApiDocBundle\Controller\SwaggerUiController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class Listener
{
    private string $requestId;

    private ?string $routeName = null;

    private ?string $clientToken = null;

    private ?string $version = null;

    /**
     * @var array<string>
     */
    private array $docPaths = ['/api/doc', '/api/doc.json'];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ApiClientRepository $apiClientsRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $this->initRequestParams($request);

        if (null === $this->clientToken || 'prod' !== getenv('APP_ENV')) {
            $this->logInputAndHeaders($request);
        }
        if (null === $this->clientToken && !in_array($request->getPathInfo(), $this->docPaths)) {
            if ('OPTIONS' !== $request->getMethod()) {
                $event->setResponse(new Response(null, 403));
            }
        }

        $this->logger->notice(sprintf(
            '[request_id]: %s, [client_name]: %s, [client_ip]: %s, [route]: %s, [method]: %s',
            $this->requestId, $this->clientToken, $request->getClientIp(), $request->getPathInfo(),
            $request->getMethod()
        ));

        $event->getRequest()->attributes->add([
            'version' => $this->version,
            'clientToken' => $this->clientToken,
            'requestId' => $this->requestId,
        ]);
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if ($event->getController() instanceof DocumentationController
            || $event->getController() instanceof SwaggerUiController) {
            return;
        }
        $this->routeName = !empty($event->getController()[1]) ? $event->getController()[1] : null;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (empty($this->routeName)) {
            $pathSplit = explode('/', $event->getRequest()->getPathInfo());
            $this->routeName = end($pathSplit);
        }

        $response = $event->getResponse();
        if (Response::HTTP_UNAUTHORIZED === $response->getStatusCode() && \method_exists($response,
            'getMessage') && !$response instanceof AbstractExceptionInterface) {
            throw new AuthenticationException($response->getMessage());
        }

        $this->logger->notice(sprintf('[request_id]: %s, [response_code]: %s, [path]: %s, [username]: %s',
            $this->requestId, $response->getStatusCode(), $this->routeName, $this->getAuthUsername()));

        if (\in_array($response->headers->get('content-type'), ['text/html', 'text/csv', 'text/html; charset=UTF-8'])) {
            return;
        }

        $content = $response->getContent();
        if ($this->responseIsSuccess($response->getStatusCode())) {
            $content = $this->serializer->serialize((new ApiResponse())
                ->setData(!empty($response->getContent()) ? json_decode($response->getContent(), true) : null)
                ->setSuccess(true)
                ->setTime(date('Y-m-d\TH:i:sO')),
                JsonEncoder::FORMAT);
        }

        $response->setContent($content);

        $headers = [
            'Content-Language' => 'en',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($response->headers->has('Access-Control-Allow-Origin')) {
            $headers['Access-Control-Allow-Origin'] = $response->headers->get('Access-Control-Allow-Origin');
        }

        $response->headers = new ResponseHeaderBag($headers);

        $event->setResponse($response);
    }

    protected function getAuthUsername(): string
    {
        try {
            if ($token = $this->tokenStorage->getToken()) {
                if (\is_object($user = $token->getUser())) {
                    return $user->getUserIdentifier();
                }
            }
        } catch (\Exception) {
            return '';
        }

        return '';
    }

    private function logInputAndHeaders(Request $request): void
    {
        $headers = $request->headers->all();
        $routeName = $request->attributes->get('_route');
        $headersStr = '';
        foreach ($headers as $key => $value) {
            $headersStr .= "{$key}: ".current($value)."\r\n";
        }

        $prefix = !$this->clientToken ? sprintf('NO_CLIENT_%s', $routeName) : '';
        $content = $request->getContent();
        if (!$content && !empty($request->request->all())) {
            foreach ($request->request->all() as $key => $value) {
                $content .= "[{$key}]: {$value}\r\n";
            }
        }

        $this->logger->notice(sprintf('[request_id]: %s, %s__client IP: %s, input DATA: %s, HEADERS => %s',
            $this->requestId, $prefix, $request->getClientIp(), $content, $headersStr));
    }

    private function initRequestParams(Request $request): void
    {
        $this->requestId = time().mt_rand(100, 999);
        $client = $this->apiClientsRepository->getClient($request->headers->get('X-Access-Token'));
        if ($client) {
            $this->clientToken = $client->getName();
        }
        if (preg_match('/v\d/', $request->getPathInfo(), $matches)) {
            $this->version = $matches[0];
        }
    }

    private function responseIsSuccess(int $code): bool
    {
        return in_array($code, [Response::HTTP_OK, Response::HTTP_CREATED, Response::HTTP_NO_CONTENT]);
    }
}
