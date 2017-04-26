<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\DTO\Rest\Interfaces\RestDto;
use App\Entity\Interfaces\EntityInterface as Entity;
use App\Repository\Base as Repository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Abstract base class for all the application REST service classes.
 *
 * Base doctrine repository class for entities.
 *
 * @package App\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base implements Interfaces\Base
{
    /**
     * REST service entity DTO class.
     *
     * @var string
     */
    protected static $dtoClass;

    /**
     * REST service entity repository.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Validator instance.
     *
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * Class constructor.
     *
     * @param   Repository          $repository
     * @param   ValidatorInterface  $validator
     */
    public function __construct(Repository $repository, ValidatorInterface $validator)
    {
        // Store class variables
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Getter method for current entity name.
     *
     * @return  string
     */
    public function getEntityName(): string
    {
        return $this->repository->getEntityName();
    }

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
    public function getReference(string $id)
    {
        return $this->repository->getReference($id);
    }

    /**
     * Getter method for entity repository.
     *
     * @return  Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * Getter method for all associations that current entity contains.
     *
     * @return array
     */
    public function getAssociations(): array
    {
        return \array_keys($this->repository->getAssociations());
    }

    /**
     * Getter method for used DTO class for this REST service.
     *
     * @return  string
     *
     * @throws  \UnexpectedValueException
     */
    public function getDtoClass(): string
    {
        if (static::$dtoClass === null) {
            $message = \sprintf(
                'Current service class \'%s\' does\'t know what DTO class to use... Please define \'protected static $dtoClass\' to this class.',
                \get_called_class()
            );

            throw new \UnexpectedValueException($message);
        }

        return static::$dtoClass;
    }

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
        array $criteria = null,
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $search = null
    ): array {
        $criteria = $criteria ?? [];
        $orderBy = $orderBy ?? [];
        $limit = $limit ?? 0;
        $offset = $offset ?? 0;
        $search = $search ?? [];

        // Before callback method call
        $this->beforeFind($criteria, $orderBy, $limit, $offset, $search);

        // Fetch data
        $entities = $this->repository->findByWithSearchTerms($search, $criteria, $orderBy, $limit, $offset);

        // After callback method call
        $this->afterFind($criteria, $orderBy, $limit, $offset, $search, $entities);

        return $entities;
    }

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     *
     * @throws  NotFoundHttpException
     *
     * @param   string          $id
     * @param   null|boolean    $throwExceptionIfNotFound
     *
     * @return  null|Entity
     */
    public function findOne(string $id, bool $throwExceptionIfNotFound = null)
    {
        $throwExceptionIfNotFound = $throwExceptionIfNotFound ?? false;

        // Before callback method call
        $this->beforeFindOne($id);

        /** @var null|Entity $entity */
        $entity = $this->repository->find($id);

        // Entity not found
        if ($throwExceptionIfNotFound && $entity === null) {
            throw new NotFoundHttpException('Not found');
        }

        // After callback method call
        $this->afterFindOne($id, $entity);

        return $entity;
    }

    /**
     * Generic findOneBy method to return single item from database by given criteria. Return value is single entity
     * from specified repository or null if entity was not found.
     *
     * @throws  NotFoundHttpException
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|boolean    $throwExceptionIfNotFound
     *
     * @return  null|Entity
     */
    public function findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null)
    {
        $orderBy = $orderBy ?? [];
        $throwExceptionIfNotFound = $throwExceptionIfNotFound ?? false;

        // Before callback method call
        $this->beforeFindOneBy($criteria, $orderBy);

        /** @var null|Entity $entity */
        $entity = $this->repository->findOneBy($criteria, $orderBy);

        // Entity not found
        if ($throwExceptionIfNotFound && $entity === null) {
            throw new NotFoundHttpException('Not found');
        }

        // After callback method call
        $this->afterFindOneBy($criteria, $orderBy, $entity);

        return $entity;
    }

    /**
     * Generic count method to return entity count for specified criteria and search terms.
     *
     * @throws  \InvalidArgumentException
     * @throws  NoResultException
     * @throws  NonUniqueResultException
     *
     * @param   null|array  $criteria
     * @param   null|array  $search
     *
     * @return  integer
     */
    public function count(array $criteria = null, array $search = null): int
    {
        $criteria = $criteria ?? [];
        $search = $search ?? [];

        // Before callback method call
        $this->beforeCount($criteria, $search);

        $count = $this->repository->count($criteria, $search);

        // After callback method call
        $this->afterCount($criteria, $search, $count);

        return $count;
    }

    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws  ValidatorException
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     * @throws  NotFoundHttpException
     *
     * @param   RestDto $dto
     *
     * @return  Entity
     */
    public function create(RestDto $dto): Entity
    {
        // Validate DTO
        $this->validateDto($dto);

        // Determine entity name
        $entity = $this->repository->getClassName();

        /**
         * Create new entity
         *
         * @var Entity $entity
         */
        $entity = new $entity();

        // Before callback method call
        $this->beforeCreate($dto, $entity);

        // Create or update entity
        $this->persistEntity($entity, $dto);

        // After callback method call
        $this->afterCreate($dto, $entity);

        return $entity;
    }

    /**
     * Generic method to save given entity to specified repository. Return value is created entity.
     *
     * @throws  ValidatorException
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   Entity          $entity
     * @param   null|boolean    $skipValidation
     *
     * @return  Entity
     */
    public function save(Entity $entity, bool $skipValidation = null): Entity
    {
        $skipValidation = $skipValidation ?? false;

        // Before callback method call
        $this->beforeSave($entity);

        // Validate entity
        if (!$skipValidation) {
            $errors = $this->validator->validate($entity);

            // Oh noes, we have some errors
            if (\count($errors) > 0) {
                throw new ValidatorException((string)$errors);
            }
        }

        // Persist on database
        $this->repository->save($entity);

        // After callback method call
        $this->afterSave($entity);

        return $entity;
    }

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws  NotFoundHttpException
     * @throws  ValidatorException
     * @throws  ORMInvalidArgumentException
     * @throws  OptimisticLockException
     *
     * @param   string  $id
     * @param   RestDto $dto
     *
     * @return  Entity
     */
    public function update(string $id, RestDto $dto): Entity
    {
        // Fetch entity
        $entity = $this->getEntity($id);

        // Validate DTO
        $this->validateDto($dto);

        // Before callback method call
        $this->beforeUpdate($id, $dto, $entity);

        // Create or update entity
        $this->persistEntity($entity, $dto);

        // After callback method call
        $this->afterUpdate($id, $dto, $entity);

        return $entity;
    }

    /**
     * Generic method to delete specified entity from database.
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws NotFoundHttpException
     *
     * @param   string  $id
     *
     * @return  Entity
     */
    public function delete(string $id): Entity
    {
        // Fetch entity
        $entity = $this->getEntity($id);

        // Before callback method call
        $this->beforeDelete($id, $entity);

        // And remove entity from repo
        $this->repository->remove($entity);

        // After callback method call
        $this->afterDelete($id, $entity);

        return $entity;
    }

    /**
     * Generic ids method to return an array of id values from database. Return value is an array of specified
     * repository entity id values.
     *
     * @throws  \InvalidArgumentException
     *
     * @param   null|array  $criteria
     * @param   null|array  $search
     *
     * @return  array
     */
    public function getIds(array $criteria = null, array $search = null): array
    {
        $criteria = $criteria ?? [];
        $search = $search ?? [];

        // Before callback method call
        $this->beforeIds($criteria, $search);

        // Fetch data
        $ids = $this->repository->findIds($criteria, $search);

        // After callback method call
        $this->afterIds($ids, $criteria, $search);

        return $ids;
    }

    /**
     * Before lifecycle method for find method.
     *
     * @param   array   $criteria
     * @param   array   $orderBy
     * @param   integer $limit
     * @param   integer $offset
     * @param   array   $search
     */
    public function beforeFind(array &$criteria, array &$orderBy, int &$limit, int &$offset, array &$search)
    {
    }

    /**
     * After lifecycle method for find method.
     *
     * @param   array       $criteria
     * @param   array       $orderBy
     * @param   integer     $limit
     * @param   integer     $offset
     * @param   array       $search
     * @param   Entity[]    $entities
     */
    public function afterFind(
        array &$criteria,
        array &$orderBy,
        int &$limit,
        int &$offset,
        array &$search,
        array &$entities
    ) {
    }

    /**
     * Before lifecycle method for findOne method.
     *
     * @param   string  $id
     */
    public function beforeFindOne(string &$id)
    {
    }

    /**
     * After lifecycle method for findOne method.
     *
     * @param   string      $id
     * @param   null|Entity $entity
     */
    public function afterFindOne(string &$id, Entity $entity = null)
    {
    }

    /**
     * Before lifecycle method for findOneBy method.
     *
     * @param   array   $criteria
     * @param   array   $orderBy
     */
    public function beforeFindOneBy(array &$criteria, array &$orderBy)
    {
    }

    /**
     * After lifecycle method for findOneBy method.
     *
     * @param   array       $criteria
     * @param   array       $orderBy
     * @param   null|Entity $entity
     */
    public function afterFindOneBy(array &$criteria, array &$orderBy, Entity $entity = null)
    {
    }

    /**
     * Before lifecycle method for count method.
     *
     * @param   array       $criteria
     * @param   null|array  $search
     */
    public function beforeCount(array &$criteria, array &$search)
    {
    }

    /**
     * Before lifecycle method for count method.
     *
     * @param   array       $criteria
     * @param   null|array  $search
     * @param   integer     $count
     */
    public function afterCount(array &$criteria, array &$search, int &$count)
    {
    }

    /**
     * Before lifecycle method for create method.
     *
     * @param   RestDto $dto
     * @param   Entity  $entity
     */
    public function beforeCreate(RestDto $dto, Entity $entity)
    {
    }

    /**
     * After lifecycle method for create method.
     *
     * @param   RestDto $dto
     * @param   Entity  $entity
     */
    public function afterCreate(RestDto $dto, Entity $entity)
    {
    }

    /**
     * Before lifecycle method for save method.
     *
     * @param   Entity  $entity
     */
    public function beforeSave(Entity $entity)
    {
    }

    /**
     * After lifecycle method for save method.
     *
     * @param   Entity  $entity
     */
    public function afterSave(Entity $entity)
    {
    }

    /**
     * Before lifecycle method for update method.
     *
     * @param   string  $id
     * @param   RestDto $dto
     * @param   Entity  $entity
     */
    public function beforeUpdate(string &$id, RestDto $dto, Entity $entity)
    {
    }

    /**
     * After lifecycle method for update method.
     *
     * @param   string  $id
     * @param   RestDto $dto
     * @param   Entity  $entity
     */
    public function afterUpdate(string &$id, RestDto $dto, Entity $entity)
    {
    }

    /**
     * Before lifecycle method for delete method.
     *
     * @param   string  $id
     * @param   Entity  $entity
     */
    public function beforeDelete(string &$id, Entity $entity)
    {
    }

    /**
     * After lifecycle method for delete method.
     *
     * @param   string  $id
     * @param   Entity  $entity
     */
    public function afterDelete(string &$id, Entity $entity)
    {
    }

    /**
     * Before lifecycle method for ids method.
     *
     * @param   array   $criteria
     * @param   array   $search
     */
    public function beforeIds(array &$criteria, array &$search)
    {
    }

    /**
     * Before lifecycle method for ids method.
     *
     * @param   array   $criteria
     * @param   array   $search
     * @param   array   $ids
     */
    public function afterIds(array &$criteria, array &$search, array &$ids)
    {
    }

    /**
     * Helper method to set data to specified entity and store it to database.
     *
     * @throws  ValidatorException
     * @throws  OptimisticLockException
     * @throws  NotFoundHttpException
     * @throws  ORMInvalidArgumentException
     *
     * @param   Entity  $entity
     * @param   RestDto $dto
     */
    protected function persistEntity(Entity $entity, RestDto $dto)
    {
        // Update entity according to DTO current state
        $dto->update($entity);

        // And save current entity
        $this->save($entity);
    }

    /**
     * Helper method to validate given DTO class.
     *
     * @throws  ValidatorException
     *
     * @param   RestDto $dto
     */
    private function validateDto(RestDto $dto)
    {
        // Check possible errors of DTO
        $errors = $this->validator->validate($dto);

        // Oh noes, we have some errors
        if (\count($errors) > 0) {
            throw new ValidatorException((string)$errors);
        }
    }

    /**
     * @throws  NotFoundHttpException
     *
     * @param   string $id
     *
     * @return  Entity
     */
    private function getEntity(string $id): Entity
    {
        /** @var Entity $entity */
        $entity = $this->repository->find($id);

        // Entity not found
        if ($entity === null) {
            throw new NotFoundHttpException('Not found');
        }

        return $entity;
    }
}
