<?php

namespace App\Traits\Repository;

use App\Facades\Logger;
use App\Support\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use function ceil;
use function count;

trait CRUD
{
    public function save($entity, bool $flush = true): bool
    {
        try {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->flush();
            }

            return true;
        } catch (Exception $e) {
            Logger::warning($e->getMessage(), ['exception' => $e]);
        }

        return false;
    }

    public function delete($entity, bool $flush = true): void
    {
        try {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (Exception $e) {
            Logger::warning($e->getMessage(), ['exception' => $e]);
        }
    }

    public function flush(): void
    {
        try {
            $this->getEntityManager()->flush();
        } catch (Exception $e) {
            Logger::warning($e->getMessage(), ['exception' => $e]);
        }
    }

    public function findAll(bool $returnArray = false): array|Collection
    {
        if ($returnArray) {
            return $this->createQueryBuilder('e')
                ->select('e')
                ->getQuery()
                ->getArrayResult();
        }

        return collection(parent::findAll());
    }

    public function findByIds(array $ids, string $column = 'id', bool $orderByIds = false): Collection
    {
        $results = [];

        if (count($ids)) {
            $qb = $this->createQueryBuilder('e');
            $qb->select('e')
                ->where($qb->expr()->in('e.' . $column, $ids));

            if ($orderByIds) {
                $qb->orderBy("FIELD(e.$column, :ids)")
                    ->setParameter('ids', $ids);
            }

            $results = $qb->getQuery()->getResult();
        }

        return collection($results);
    }

    public function findBySlug(string $slug): mixed
    {
        try {
            $qb = $this->createQueryBuilder('e')
                ->andWhere('e.slug = :slug')
                ->setParameter('slug', $slug);

            return $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
        } catch (Exception $e) {
            Logger::warning($e->getMessage(), ['exception' => $e]);
        }

        return null;
    }

    #[ArrayShape(['count' => "int|null", 'pages' => "int", 'results' => "mixed"])]
    public function getPaginatedResults($qb, int $page = 1, int $maxResults = 10): array
    {
        $paginator = new Paginator($qb);

        $total = $paginator->count();
        $pages = (int)ceil($total / $maxResults);

        $firstResult = $maxResults * ($page - 1);

        return [
            'count'   => $total,
            'pages'   => $pages,
            'results' => $paginator->getQuery()->setFirstResult($firstResult)->setMaxResults($maxResults)->getResult()
        ];
    }

    public function executeDQLWithResult(string $dql, ?array $params = null, ?int $max = null, ?int $offset = null, ?string $options = self::ENTITY_RESULT): mixed
    {
        try {
            $query = $this->getEntityManager()->createQuery($dql)->useQueryCache(true);

            if ($params !== null && count($params) > 0) {
                $query->setParameters($params);
            }

            if ($max !== null) {
                $query->setMaxResults($max);
            }

            if ($offset !== null) {
                $query->setFirstResult($offset);
            }

            switch ($options) {
                case self::ARRAY_RESULT:
                    return $query->getArrayResult();
                case self::EXECUTE:
                    $query->execute();
                    break;
                case self::ONE_OR_NULL_RESULT:
                    return $query->getOneOrNullResult();
                case self::SINGLE_SCALAR_RESULT:
                    return $query->getSingleScalarResult();
                case self::SINGLE_COLUMN_RESULT:
                    return $query->getSingleColumnResult();
                default:
                    return $query->getResult();
            }
        } catch (NonUniqueResultException|NoResultException $e) {
            Logger::warning($e->getMessage(), ['exception' => $e]);
        }

        return null;
    }

    #[ArrayShape(['count' => "int|null", 'pages' => "int", 'results' => "mixed"])]
    public function executeDQLWithPagination(string $dql, ?array $params = null, int $page = 1, int $resultsPerPage = 10): ?array
    {
        $query = $this->getEntityManager()->createQuery($dql);

        if ($params && (count($params) > 0)) {
            $query->setParameters($params);
        }

        return $this->getPaginatedResults($query, $page, $resultsPerPage);
    }

    public function executeDQLWithOneOrNullResult(string $dql, ?array $params = null, ?int $offset = null): mixed
    {
        return $this->executeDQLWithResult($dql, $params, 1, $offset, self::ONE_OR_NULL_RESULT);
    }

    public function executeDQLWithArrayResult(string $dql, ?array $params = null, ?int $max = null): mixed
    {
        return $this->executeDQLWithResult($dql, $params, $max, null, self::ARRAY_RESULT);
    }

    public function executeDQLWithSingleColumnResult(string $dql, ?array $params = null): array
    {
        return $this->executeDQLWithResult($dql, $params, null, null, self::SINGLE_COLUMN_RESULT);
    }

    public function executeDQLWithSingleScalarResult(string $dql, ?array $params = null): mixed
    {
        return $this->executeDQLWithResult($dql, $params, null, null, self::SINGLE_SCALAR_RESULT);
    }

    public function executeDQL(string $dql, ?array $params = null): void
    {
        $this->executeDQLWithResult($dql, $params, null, null, self::EXECUTE);
    }
}