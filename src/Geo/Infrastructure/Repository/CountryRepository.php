<?php

declare(strict_types=1);

namespace App\Geo\Infrastructure\Repository;

use App\Geo\Domain\Entity\Country;
use App\Geo\Domain\Repository\CountryRepositoryInterface;
use App\Geo\Infrastructure\Exception\CountryNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository implements CountryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @return array<Country>
     */
    public function getList(string $search, int $offset, int $limit): array
    {
        $stmt = $this->_em->createQueryBuilder()
            ->select('c FROM App\Geo\Domain\Entity\Country c')
            ->orderBy('c.sortPriority');

        if ($search) {
            $search = preg_replace('/[^a-zA-Zа-яёЁ]/iu', '', $search);
            if (!$search) {
                return [];
            }
            $stmt
                ->andWhere('LOWER(c.title) LIKE :search')
                ->setParameter('search', '%'.addcslashes(mb_strtolower($search), '%_').'%')
                ->addOrderBy('c.title');
        }

        if ($limit) {
            $stmt->setFirstResult($offset);
            $stmt->setMaxResults($limit);
        }

        return $stmt->getQuery()->getResult();
    }

    public function getIsoByAlpha(string $alpha): string
    {
        $country = $this->findOneBy(['codeAlpha' => $alpha]);
        if (null === $country) {
            throw new CountryNotFoundException(sprintf('country not found: %s', $alpha));
        }

        return $country->getCodeIso();
    }

    /**
     * @return array<Country>
     */
    public function getAll(): array
    {
        return $this->findAll();
    }
}
