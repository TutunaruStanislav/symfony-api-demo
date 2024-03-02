<?php

declare(strict_types=1);

namespace App\Geo\Infrastructure\Controller;

use App\Geo\Application\Model\City\CityListItem;
use App\Geo\Application\Model\City\CityListResponse;
use App\Geo\Application\Model\City\CityNearestRequest;
use App\Geo\Application\Model\City\CityRequest;
use App\Geo\Application\Service\CityService;
use Fusonic\HttpKernelBundle\Attribute\FromRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    public function __construct(private readonly CityService $cityService)
    {
    }

    #[Route(path: '/api/v1/cities', name: 'get_cities', methods: ['GET'])]
    #[OA\Parameter(name: 'offset', description: 'Offset selection', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'limit', description: 'Limit selection', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'search', description: 'Search string', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'countryCode', description: 'Country alpha-3 code (ISO 3166)', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'X-Access-Token', description: 'Access token', in: 'header', required: true, schema: new OA\Schema(type: 'string', default: '3dbe06033befda5cc41c5fa2197efcb6'))]
    #[Security(name: 'bearerAuth')]
    #[OA\Response(response: 200, description: 'OK', content: new Model(type: CityListResponse::class))]
    #[OA\Response(response: 401, description: 'Authorization error')]
    #[OA\Response(response: 403, description: 'Common error')]
    #[OA\Response(response: 422, description: 'Input params validation errors')]
    public function getCities(#[FromRequest] CityRequest $request): Response
    {
        return $this->json($this->cityService->getList($request));
    }

    #[Route(path: '/api/v1/cities/nearest', name: 'get_nearest_city', methods: ['GET'])]
    #[OA\Parameter(name: 'lat', description: 'Latitude', in: 'query', schema: new OA\Schema(type: 'float', default: 55.5))]
    #[OA\Parameter(name: 'lng', description: 'Longitude', in: 'query', schema: new OA\Schema(type: 'float', default: 37.2))]
    #[OA\Parameter(name: 'X-Access-Token', description: 'Access token', in: 'header', required: true, schema: new OA\Schema(type: 'string', default: '3dbe06033befda5cc41c5fa2197efcb6'))]
    #[Security(name: 'bearerAuth')]
    #[OA\Response(response: 200, description: 'OK', content: new Model(type: CityListItem::class))]
    #[OA\Response(response: 401, description: 'Authorization error')]
    #[OA\Response(response: 403, description: 'Common error')]
    #[OA\Response(response: 422, description: 'Input params validation errors')]
    public function getNearestCity(#[FromRequest] CityNearestRequest $request): Response
    {
        return $this->json($this->cityService->getNearestCity($request));
    }
}
