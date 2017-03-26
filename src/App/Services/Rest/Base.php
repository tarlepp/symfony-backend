<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\Entity\Interfaces\EntityInterface as Entity;
use App\Repository\Base as Repository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
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
     * Ignored properties on persist entity call. Define these on your service class.
     *
     * @var array
     */
    protected static $ignoredPropertiesOnPersistEntity = [];

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
     * {@inheritdoc}
     */
    public function __construct(Repository $repository, ValidatorInterface $validator)
    {
        // Store class variables
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityName(): string
    {
        return $this->repository->getEntityName();
    }

    /**
     * {@inheritdoc}
     */
    public function getReference(string $id)
    {
        return $this->repository->getReference($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociations(): array
    {
        return \array_keys($this->repository->getAssociations());
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function create(\stdClass $data): Entity
    {
        // Determine entity name
        $entity = $this->repository->getClassName();

        /**
         * Create new entity
         *
         * @var Entity $entity
         */
        $entity = new $entity();

        // Before callback method call
        $this->beforeCreate($data, $entity);

        // Create or update entity
        $this->persistEntity($entity, $data);

        // After callback method call
        $this->afterCreate($data, $entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function update(string $id, \stdClass $data): Entity
    {
        /** @var Entity $entity */
        $entity = $this->repository->find($id);

        // Entity not found
        if ($entity === null) {
            throw new NotFoundHttpException('Not found');
        }

        // Before callback method call
        $this->beforeUpdate($id, $data, $entity);

        // Create or update entity
        $this->persistEntity($entity, $data);

        // After callback method call
        $this->afterUpdate($id, $data, $entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $id): Entity
    {
        /** @var Entity $entity */
        $entity = $this->repository->find($id);

        // Entity not found
        if ($entity === null) {
            throw new NotFoundHttpException('Not found');
        }

        // Before callback method call
        $this->beforeDelete($id, $entity);

        // And remove entity from repo
        $this->repository->remove($entity);

        // After callback method call
        $this->afterDelete($id, $entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function beforeFind(array &$criteria, array &$orderBy, int &$limit, int &$offset, array &$search)
    {
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function beforeFindOne(string &$id)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterFindOne(string &$id, Entity $entity = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFindOneBy(array &$criteria, array &$orderBy)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterFindOneBy(array &$criteria, array &$orderBy, Entity $entity = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCount(array &$criteria, array &$search)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterCount(array &$criteria, array &$search, int &$count)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCreate(\stdClass $data, Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterCreate(\stdClass $data, Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave(Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave(Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeUpdate(string &$id, \stdClass $data, Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterUpdate(string &$id, \stdClass $data, Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete(string &$id, Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete(string &$id, Entity $entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeIds(array &$criteria, array &$search)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function afterIds(array &$criteria, array &$search, array &$ids)
    {
    }

    /**
     * Helper method to set data to specified entity and store it to database.
     *
     * @todo    should this throw an error, if given data contains something else than entity itself?
     * @todo    should this throw an error, if setter method doesn't exists?
     *
     * @throws  ValidatorException
     * @throws  OptimisticLockException
     * @throws  NotFoundHttpException
     * @throws  ORMInvalidArgumentException
     *
     * @param   Entity $entity
     * @param   \stdClass $data
     */
    protected function persistEntity(Entity $entity, \stdClass $data)
    {
        // Specify properties that are not allowed to update by user
        $ignoreProperties = \array_merge(
            [
                'createdAt', 'createdBy',
                'updatedAt', 'updatedBy'
            ],
            static::$ignoredPropertiesOnPersistEntity
        );

        // Determine associations for current entity
        $associations = $this->getRepository()->getAssociations();

        // And fetch meta data for entity
        $meta = $this->getRepository()->getEntityManager()->getClassMetadata($this->getEntityName());

        // Iterate given data
        foreach ($data as $property => $value) {
            $type = $meta->getTypeOfField($property);

            if (\in_array($property, $ignoreProperties, true)) {
                continue;
            }

            if (\array_key_exists($property, $associations)) {
                $value = $this->determineAssociationValue($associations[$property], $value);
            } elseif ($type === 'date') {
                $value = new \DateTime($value, new \DateTimeZone('UTC'));
            }

            // Specify setter method for current property
            $method = \sprintf(
                'set%s',
                \ucwords($property)
            );

            // Yeah method exists, so use it with current value
            if (\method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }

        // And save current entity
        $this->save($entity);
    }

    /**
     * Helper method to determine association entity value.
     *
     * @throws  NotFoundHttpException
     *
     * @param   array   $association
     * @param   mixed   $value
     *
     * @return  Entity
     */
    private function determineAssociationValue(array $association, $value): Entity
    {
        // Get repository class for current association entity
        $repository = $this->getRepository()->getEntityManager()->getRepository($association['targetEntity']);

        /** @var Entity $entity */
        $entity = $repository->findOneBy(['id' => $value instanceof \stdClass ? $value->id : $value]);

        // Oh noes association entity not found - darn...
        if ($entity === null) {
            $message = \sprintf(
                "Cannot find record for '%s' with id '%s'",
                $association['fieldName'],
                $value instanceof \stdClass ? $value->id : $value
            );

            throw new NotFoundHttpException($message);
        }

        return $entity;
    }
}
