<?php

declare(strict_types=1);

namespace App\Geo\Infrastructure\Controller;

use App\Geo\Application\Model\Country\CountryListResponse;
use App\Geo\Application\Model\Country\CountryRequest;
use App\Geo\Application\Service\CountryService;
use Fusonic\HttpKernelBundle\Attribute\FromRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    public function __construct(private readonly CountryService $countryService)
    {
    }

    #[Route(path: '/api/v1/countries', name: 'get_countries', methods: ['GET'])]
    #[OA\Parameter(name: 'offset', description: 'Offset selection', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'limit', description: 'Limit selection', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'search', description: 'Search string', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'X-Access-Token', description: 'Access token', in: 'header', required: true, schema: new OA\Schema(type: 'string', default: '3dbe06033befda5cc41c5fa2197efcb6'))]
    #[Security(name: 'bearerAuth')]
    #[OA\Response(response: 200, description: 'OK', content: new Model(type: CountryListResponse::class))]
    #[OA\Response(response: 401, description: 'Authorization error')]
    #[OA\Response(response: 403, description: 'Common error')]
    #[OA\Response(response: 422, description: 'Input params validation errors')]
    public function getCountries(#[FromRequest] CountryRequest $request): Response
    {
        return $this->json($this->countryService->getList($request));
    }
}
