<?php
declare(strict_types=1);
/**
 * /src/App/Repository/Interfaces/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository\Interfaces;

use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;

/**
 * Generic interface for all application repository classes that extends Base repository.
 *
 * @package App\Repository\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Base
{
    /**
     * Getter method for entity name.
     *
     * @return  string
     */
    public function getEntityName(): string;

    /**
     * Gets a reference to the entity identified by the given type and identifier without actually loading it,
     * if the entity is not yet loaded.
     *
     * @throws  \Doctrine\ORM\ORMException
     *
     * @param   string $id
     *
     * @return  \Doctrine\Common\Proxy\Proxy
     */
    public function getReference(string $id);

    /**
     * Gets all association mappings of the class.
     *
     * @return  array
     */
    public function getAssociations(): array;

    /**
     * Getter method for search columns of current entity.
     *
     * @return string[]
     */
    public function getSearchColumns(): array;

    /**
     * Helper method to persist specified entity to database.
     *
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   EntityInterface $entity
     *
     * @return  Base
     */
    public function save(EntityInterface $entity): Base;

    /**
     * Helper method to remove specified entity from database.
     *
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   EntityInterface $entity
     *
     * @return  Base
     */
    public function remove(EntityInterface $entity): Base;

    /**
     * Generic count method to determine count of entities for specified criteria and search term(s).
     *
     * @throws  \InvalidArgumentException
     * @throws  NoResultException
     * @throws  NonUniqueResultException
     *
     * @param   array   $criteria
     * @param   array   $search
     *
     * @return  integer
     */
    public function count(array $criteria = [], array $search = []): int;

    /**
     * Generic replacement for basic 'findBy' method if/when you want to use generic LIKE search.
     *
     * @throws  \InvalidArgumentException
     *
     * @param   array           $search
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     *
     * @return  array
     */
    public function findByWithSearchTerms(
        array $search,
        array $criteria,
        array $orderBy = [],
        int $limit = null,
        int $offset = null
    ): array;

    /**
     * Repository method to fetch current entity id values from database and return those as an array.
     *
     * @throws  \InvalidArgumentException
     *
     * @param   array   $criteria
     * @param   array   $search
     *
     * @return  array
     */
    public function findIds(array $criteria = [], array $search = []): array;

    /**
     * Helper method to 'reset' repository entity table - in other words delete all records - so be carefully with
     * this...
     *
     * @return  integer
     */
    public function reset(): int;
}
