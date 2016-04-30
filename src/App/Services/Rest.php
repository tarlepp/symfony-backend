<?php
/**
 * /src/App/Repository/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Application components
use App\Entity\Base as Entity;
use App\Entity\Interfaces\Base as EntityInterface;
use App\Repository\Base as AppEntityRepository;

// Doctrine components
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

// Symfony components
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Abstract base class for all the application REST service classes.
 *
 * Base doctrine repository class for entities.
 *
 * @category    Service
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Rest extends Base implements Interfaces\Rest
{
    /**
     * REST service entity repository.
     *
     * @var AppEntityRepository
     */
    protected $repository;

    /**
     * Validator instance.
     *
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * Entity manager object.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Name of the current entity.
     *
     * @var string
     */
    protected $entityName;

    /**
     * Class constructor.
     *
     * @param   EntityRepository    $repository
     * @param   ValidatorInterface  $validator
     */
    public function __construct(EntityRepository $repository, ValidatorInterface $validator)
    {
        // Store class variables
        $this->repository = $repository;
        $this->validator = $validator;

        // Store entity name and manager
        $this->entityName = $this->repository->getClassName();
        $this->entityManager = $this->repository->getEntityManager();
    }

    /**
     * Getter method for entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Getter method for current repository.
     *
     * @return  AppEntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Gets a reference to the entity identified by the given type and identifier without actually loading it,
     * if the entity is not yet loaded.
     *
     * @throws  \Doctrine\ORM\ORMException
     *
     * @param   mixed   $id The entity identifier.
     *
     * @return  bool|\Doctrine\Common\Proxy\Proxy|null|object
     */
    public function getReference($id)
    {
        return $this->entityManager->getReference($this->entityName, $id);
    }

    /**
     * Getter method for all associations that current entity contains.
     *
     * @return array
     */
    public function getAssociations()
    {
        return array_keys($this->entityManager->getClassMetadata($this->entityName)->getAssociationMappings());
    }

    /**
     * Generic find method to return an array of items from database. Return value is an array of specified repository
     * entities.
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     *
     * @return  Entity[]
     */
    public function find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        // Before callback method call
        if (method_exists($this, 'beforeFind')) {
            $this->beforeFind($criteria, $orderBy, $limit, $offset);
        }

        // Fetch data
        $entities = $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);

        // After callback method call
        if (method_exists($this, 'afterFind')) {
            $this->afterFind($criteria, $orderBy, $limit, $offset, $entities);
        }

        return $entities;
    }

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     *
     * @param   integer $id
     *
     * @return  null|Entity
     */
    public function findOne($id)
    {
        // Before callback method call
        if (method_exists($this, 'beforeFindOne')) {
            $this->beforeFindOne($id);
        }

        $entity = $this->getRepository()->find($id);

        // After callback method call
        if (method_exists($this, 'afterFindOne')) {
            $this->afterFindOne($id, $entity);
        }

        return $entity;
    }

    /**
     * Generic findOneBy method to return single item from database by given criteria. Return value is single entity
     * from specified repository or null if entity was not found.
     *
     * @param   array       $criteria
     * @param   null|array  $orderBy
     *
     * @return  null|Entity
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        // Before callback method call
        if (method_exists($this, 'beforeFindOneBy')) {
            $this->beforeFindOneBy($criteria, $orderBy);
        }

        $entity = $this->getRepository()->findOneBy($criteria, $orderBy);

        // After callback method call
        if (method_exists($this, 'afterFindOneBy')) {
            $this->afterFindOneBy($criteria, $orderBy, $entity);
        }

        return $entity;
    }

    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @throws  ValidatorException
     *
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function create(\stdClass $data)
    {
        // Determine entity name
        $entity = $this->getRepository()->getClassName();

        /**
         * Create new entity
         *
         * @var Entity $entity
         */
        $entity = new $entity();

        // Before callback method call
        if (method_exists($this, 'beforeCreate')) {
            $this->beforeCreate($data, $entity);
        }

        // Create or update entity
        $this->persistEntity($entity, $data);

        // After callback method call
        if (method_exists($this, 'afterCreate')) {
            $this->afterCreate($data, $entity);
        }

        return $entity;
    }

    /**
     * Generic method to save given entity to specified repository. Return value is created entity.
     *
     * @throws  ValidatorException
     *
     * @param   EntityInterface $entity
     *
     * @return  Entity
     */
    public function save(EntityInterface $entity)
    {
        // Before callback method call
        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave($entity);
        }

        // Validate entity
        $errors = $this->validator->validate($entity);

        // Oh noes, we have some errors
        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

        // Persist on database
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);

        // After callback method call
        if (method_exists($this, 'afterSave')) {
            $this->afterSave($entity);
        }

        return $entity;
    }

    /**
     * Generic method to update specified entity with new data.
     *
     * @throws  HttpException
     * @throws  ValidatorException
     *
     * @param   integer     $id
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function update($id, \stdClass $data)
    {
        /** @var Entity $entity */
        $entity = $this->getRepository()->find($id);

        // Entity not found
        if (is_null($entity)) {
            throw new HttpException(404, 'Not found');
        }

        // Before callback method call
        if (method_exists($this, 'beforeUpdate')) {
            $this->beforeUpdate($id, $data, $entity);
        }

        // Create or update entity
        $this->persistEntity($entity, $data);

        // After callback method call
        if (method_exists($this, 'afterUpdate')) {
            $this->afterUpdate($id, $data, $entity);
        }

        return $entity;
    }

    /**
     * Generic method to delete specified entity from database.
     *
     * @param   integer $id
     *
     * @return  Entity
     */
    public function delete($id)
    {
        /** @var Entity $entity */
        $entity = $this->getRepository()->find($id);

        // Entity not found
        if (is_null($entity)) {
            throw new HttpException(404, 'Not found');
        }

        // Before callback method call
        if (method_exists($this, 'beforeDelete')) {
            $this->beforeDelete($id, $entity);
        }

        // And remove entity from repo
        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);

        // After callback method call
        if (method_exists($this, 'afterDelete')) {
            $this->afterDelete($id, $entity);
        }

        return $entity;
    }

    /**
     * Before lifecycle method for find method.
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     */
    public function beforeFind(array &$criteria = [], array &$orderBy = null, &$limit = null, &$offset = null) { }

    /**
     * After lifecycle method for find method.
     *
     * @param   array        $criteria
     * @param   null|array   $orderBy
     * @param   null|integer $limit
     * @param   null|integer $offset
     * @param   Entity[]     $entities
     */
    public function afterFind(
        array &$criteria = [],
        array &$orderBy = null,
        &$limit = null,
        &$offset = null,
        array &$entities = []
    ) { }

    /**
     * Before lifecycle method for findOne method.
     *
     * @param   integer $id
     */
    public function beforeFindOne(&$id) { }

    /**
     * After lifecycle method for findOne method.
     *
     * @param   integer     $id
     * @param   null|Entity $entity
     */
    public function afterFindOne(&$id, Entity $entity = null) { }

    /**
     * Before lifecycle method for findOneBy method.
     *
     * @param   array       $criteria
     * @param   null|array  $orderBy
     */
    public function beforeFindOneBy(array &$criteria, array &$orderBy = null) { }

    /**
     * After lifecycle method for findOneBy method.
     *
     * @param   array       $criteria
     * @param   null|array  $orderBy
     * @param   null|Entity $entity
     */
    public function afterFindOneBy(array &$criteria, array &$orderBy = null,  Entity $entity = null) { }

    /**
     * Before lifecycle method for create method.
     *
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function beforeCreate(\stdClass $data, Entity $entity) { }

    /**
     * After lifecycle method for create method.
     *
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function afterCreate(\stdClass $data, Entity $entity) { }

    /**
     * Before lifecycle method for save method.
     *
     * @param   EntityInterface $entity
     */
    public function beforeSave(EntityInterface $entity) { }

    /**
     * After lifecycle method for save method.
     *
     * @param   EntityInterface $entity
     */
    public function afterSave(EntityInterface $entity) { }

    /**
     * Before lifecycle method for update method.
     *
     * @param   integer         $id
     * @param   \stdClass       $data
     * @param   EntityInterface $entity
     */
    public function beforeUpdate(&$id, \stdClass $data, EntityInterface $entity) { }

    /**
     * After lifecycle method for update method.
     *
     * @param   integer         $id
     * @param   \stdClass       $data
     * @param   EntityInterface $entity
     */
    public function afterUpdate(&$id, \stdClass $data, EntityInterface $entity) { }

    /**
     * Before lifecycle method for delete method.
     *
     * @param   integer         $id
     * @param   EntityInterface $entity
     */
    public function beforeDelete(&$id, EntityInterface $entity) { }

    /**
     * After lifecycle method for delete method.
     *
     * @param   integer         $id
     * @param   EntityInterface $entity
     */
    public function afterDelete(&$id, EntityInterface $entity) { }

    /**
     * Helper method to set data to specified entity and store it to database.
     *
     * @todo    should this throw an error, if given data contains something else than entity itself?
     * @todo    should this throw an error, if setter method doesn't exists?
     *
     * @param   EntityInterface $entity
     * @param   \stdClass       $data
     *
     * @return  void
     */
    protected function persistEntity(EntityInterface $entity, \stdClass $data)
    {
        // Specify properties that are not allowed to update by user
        $ignoreProperties = [
            'createdAt', 'createdBy',
            'updatedAt', 'updatedBy',
        ];

        // Iterate given data
        foreach ($data as $property => $value) {
            if (in_array($property, $ignoreProperties)) {
                continue;
            }

            // Specify setter method for current property
            $method = sprintf(
                'set%s',
                ucwords($property)
            );

            // Yeah method exists, so use it with current value
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }

        // And save current entity
        $this->save($entity);
    }
}
