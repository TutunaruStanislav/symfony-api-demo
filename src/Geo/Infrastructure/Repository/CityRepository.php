<?php

declare(strict_types=1);

namespace App\Geo\Infrastructure\Repository;

use App\Geo\Domain\Entity\City;
use App\Geo\Domain\Repository\CityRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository implements CityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function getList(int $offset, int $limit, string $search, string $country): mixed
    {
        $query = $this->initQueryForListAndTotal($search, $country, offset: $offset, limit: $limit);

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getTotal(string $search, string $country): int
    {
        $query = $this->initQueryForListAndTotal($search, $country, true);

        return $query->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getNearest(float $lat, float $lng): mixed
    {
        $stmt = $this->_em->createQuery('SELECT c FROM App\Geo\Domain\Entity\City c
            WHERE c.latitude BETWEEN (?1 - 0.5) AND (?1 + 0.5)
            AND c.longitude BETWEEN (?2 - 0.5) AND (?2 + 0.5)
            ORDER BY ABS(c.latitude - ?1) + ABS(c.longitude - ?2)
        ')
            ->setParameters([
                1 => $lat,
                2 => $lng,
            ])
            ->setMaxResults(1);

        return $stmt->getOneOrNullResult();
    }

    private function initQueryForListAndTotal(string $search, string $country, bool $onlyCount = false, int $offset = null, int $limit = null): Query
    {
        $selectCondition = $onlyCount ? 'COUNT(c)' : 'c';
        $codeCondition = !empty($country) ? 'AND c.countryCode = :country' : '';
        $searchCondition = !empty($search) ? 'AND LOWER(c.title) LIKE :search' : '';
        $orderCondition = !$onlyCount ? 'ORDER BY c.countryCode ASC, c.title ASC' : '';
        $query = $this->_em->createQuery("SELECT {$selectCondition} FROM App\Geo\Domain\Entity\City c
            WHERE 1=1 {$codeCondition} {$searchCondition} {$orderCondition}"
        );

        if (!empty($country)) {
            $query->setParameter('country', $country);
        }
        if (!empty($search)) {
            $search = preg_replace('/[^a-zA-Zа-яёЁ]/iu', '', $search);
            $query->setParameter('search', '%'.addcslashes(mb_strtolower($search), '%_').'%');
        }
        if (null !== $offset && null !== $limit) {
            $query
                ->setFirstResult($offset)
                ->setMaxResults($limit);
        }

        return $query;
    }
}
