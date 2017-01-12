<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Interfaces/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest\Interfaces;

use App\Entity\Interfaces\EntityInterface as Entity;
use App\Repository\Base as Repository;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface for REST based services.
 *
 * @package App\Services\Rest\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Base
{
    /**
     * Class constructor.
     *
     * @param   Repository          $repository
     * @param   ValidatorInterface  $validator
     */
    public function __construct(Repository $repository, ValidatorInterface $validator);

    /**
     * Getter method for current entity name.
     *
     * @return  string
     */
    public function getEntityName(): string;

    /**
     * Gets a reference to the entity identified by the given type and identifier without actually loading it,
     * if the entity is not yet loaded.
     *
     * @throws  ORMException
     *
     * @param   string  $id The entity identifier.
     *
     * @return  Proxy|Entity
     */
    public function getReference(string $id);

    /**
     * Getter method for entity repository.
     *
     * @return  Repository
     */
    public function getRepository(): Repository;

    /**
     * Getter method for all associations that current entity contains.
     *
     * @return array
     */
    public function getAssociations(): array;

    /**
     * Generic find method to return an array of items from database. Return value is an array of specified repository
     * entities.
     *
     * @throws  \InvalidArgumentException
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     * @param   null|array      $search
     *
     * @return  Entity[]
     */
    public function find(
        array $criteria = [],
        array $orderBy = [],
        int $limit = null,
        int $offset = null,
        array $search = []
    ): array;

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     *
     * @throws  NotFoundHttpException
     *
     * @param   string  $id
     * @param   boolean $throwExceptionIfNotFound
     *
     * @return  null|Entity
     */
    public function findOne(string $id, bool $throwExceptionIfNotFound = false);

    /**
     * Generic findOneBy method to return single item from database by given criteria. Return value is single entity
     * from specified repository or null if entity was not found.
     *
     * @throws  NotFoundHttpException
     *
     * @param   array   $criteria
     * @param   array   $orderBy
     *
     * @return  null|Entity
     */
    public function findOneBy(array $criteria, array $orderBy = []);

    /**
     * Generic count method to return entity count for specified criteria and search terms.
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
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws  ValidatorException
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function create(\stdClass $data): Entity;

    /**
     * Generic method to save given entity to specified repository. Return value is created entity.
     *
     * @throws  ValidatorException
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   Entity  $entity
     * @param   boolean $skipValidation
     *
     * @return  Entity
     */
    public function save(Entity $entity, bool $skipValidation = false): Entity;

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws  NotFoundHttpException
     * @throws  ValidatorException
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   string      $id
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function update(string $id, \stdClass $data): Entity;

    /**
     * Generic method to delete specified entity from database.
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @param   string  $id
     *
     * @return  Entity
     */
    public function delete(string $id): Entity;

    /**
     * Generic ids method to return an array of id values from database. Return value is an array of specified
     * repository entity id values.
     *
     * @throws  \InvalidArgumentException
     *
     * @param   array   $criteria
     * @param   array   $search
     *
     * @return  array
     */
    public function getIds(array $criteria = [], array $search = []): array;

    /**
     * Before lifecycle method for find method.
     *
     * @param   array           $criteria
     * @param   array           $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     * @param   array           $search
     */
    public function beforeFind(array &$criteria, array &$orderBy, &$limit, &$offset, array &$search);

    /**
     * After lifecycle method for find method.
     *
     * @param   array           $criteria
     * @param   array           $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     * @param   array           $search
     * @param   Entity[]        $entities
     */
    public function afterFind(array &$criteria, array &$orderBy, &$limit, &$offset, array &$search, array &$entities);

    /**
     * Before lifecycle method for findOne method.
     *
     * @param   string  $id
     */
    public function beforeFindOne(string &$id);

    /**
     * After lifecycle method for findOne method.
     *
     * @param   string      $id
     * @param   null|Entity $entity
     */
    public function afterFindOne(string &$id, $entity);

    /**
     * Before lifecycle method for findOneBy method.
     *
     * @param   array   $criteria
     * @param   array   $orderBy
     */
    public function beforeFindOneBy(array &$criteria, array &$orderBy);

    /**
     * After lifecycle method for findOneBy method.
     *
     * @param   array       $criteria
     * @param   array       $orderBy
     * @param   null|Entity $entity
     */
    public function afterFindOneBy(array &$criteria, array &$orderBy, $entity);

    /**
     * Before lifecycle method for count method.
     *
     * @param   array       $criteria
     * @param   null|array  $search
     */
    public function beforeCount(array &$criteria, array &$search);

    /**
     * Before lifecycle method for count method.
     *
     * @param   array       $criteria
     * @param   null|array  $search
     * @param   integer     $count
     */
    public function afterCount(array &$criteria, array &$search, int &$count);

    /**
     * Before lifecycle method for create method.
     *
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function beforeCreate(\stdClass $data, Entity $entity);

    /**
     * After lifecycle method for create method.
     *
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function afterCreate(\stdClass $data, Entity $entity);

    /**
     * Before lifecycle method for save method.
     *
     * @param   Entity  $entity
     */
    public function beforeSave(Entity $entity);

    /**
     * After lifecycle method for save method.
     *
     * @param   Entity  $entity
     */
    public function afterSave(Entity $entity);

    /**
     * Before lifecycle method for update method.
     *
     * @param   string      $id
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function beforeUpdate(string &$id, \stdClass $data, Entity $entity);

    /**
     * After lifecycle method for update method.
     *
     * @param   string      $id
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function afterUpdate(string &$id, \stdClass $data, Entity $entity);

    /**
     * Before lifecycle method for delete method.
     *
     * @param   string  $id
     * @param   Entity  $entity
     */
    public function beforeDelete(string &$id, Entity $entity);

    /**
     * After lifecycle method for delete method.
     *
     * @param   string  $id
     * @param   Entity  $entity
     */
    public function afterDelete(string &$id, Entity $entity);

    /**
     * Before lifecycle method for ids method.
     *
     * @param   array   $criteria
     * @param   array   $search
     */
    public function beforeIds(array &$criteria, array &$search);

    /**
     * Before lifecycle method for ids method.
     *
     * @param   array   $criteria
     * @param   array   $search
     * @param   array   $ids
     */
    public function afterIds(array &$criteria, array &$search, array &$ids);
}
