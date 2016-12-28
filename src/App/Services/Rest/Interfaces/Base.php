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
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @throws  \Doctrine\ORM\ORMException
     *
     * @param   string  $id The entity identifier.
     *
     * @return  bool|\Doctrine\Common\Proxy\Proxy|null|object
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
        array $orderBy = null,
        int $limit = null,
        int $offset = null,
        array $search = []
    );

    /**
     * Generic findOne method to return single item from database. Return value is single entity from specified
     * repository.
     *
     * @param   mixed   $id
     * @param   boolean $throwExceptionIfNotFound
     *
     * @return  null|Entity
     */
    public function findOne($id, $throwExceptionIfNotFound = false);

    /**
     * Generic findOneBy method to return single item from database by given criteria. Return value is single entity
     * from specified repository or null if entity was not found.
     *
     * @param   array       $criteria
     * @param   null|array  $orderBy
     *
     * @return  null|Entity
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * Generic count method to return entity count for specified criteria and search terms.
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
     * @throws  HttpException
     * @throws  ValidatorException
     *
     * @param   mixed       $id
     * @param   \stdClass   $data
     *
     * @return  Entity
     */
    public function update($id, \stdClass $data): Entity;

    /**
     * Generic method to delete specified entity from database.
     *
     * @param   mixed   $id
     *
     * @return  Entity
     */
    public function delete($id): Entity;

    /**
     * Generic ids method to return an array of id values from database. Return value is an array of specified
     * repository entity id values.
     *
     * @param   array           $criteria
     * @param   null|array      $search
     *
     * @return array
     */
    public function getIds(array $criteria = [], array $search = []): array;

    /**
     * Before lifecycle method for find method.
     *
     * @param   array           $criteria
     * @param   null|array      $orderBy
     * @param   null|integer    $limit
     * @param   null|integer    $offset
     */
    public function beforeFind(
        array &$criteria = [],
        array &$orderBy = null,
        int &$limit = null,
        int &$offset = null
    );

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
        int &$limit = null,
        int &$offset = null,
        array &$entities = []
    );

    /**
     * Before lifecycle method for findOne method.
     *
     * @param   mixed   $id
     */
    public function beforeFindOne(&$id);

    /**
     * After lifecycle method for findOne method.
     *
     * @param   mixed       $id
     * @param   null|Entity $entity
     */
    public function afterFindOne(&$id, Entity $entity = null);

    /**
     * Before lifecycle method for findOneBy method.
     *
     * @param   array       $criteria
     * @param   null|array  $orderBy
     */
    public function beforeFindOneBy(array &$criteria, array &$orderBy = null);

    /**
     * After lifecycle method for findOneBy method.
     *
     * @param   array       $criteria
     * @param   null|array  $orderBy
     * @param   null|Entity $entity
     */
    public function afterFindOneBy(array &$criteria, array &$orderBy = null, Entity $entity = null);

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
     * @param   mixed       $id
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function beforeUpdate(&$id, \stdClass $data, Entity $entity);

    /**
     * After lifecycle method for update method.
     *
     * @param   mixed       $id
     * @param   \stdClass   $data
     * @param   Entity      $entity
     */
    public function afterUpdate(&$id, \stdClass $data, Entity $entity);

    /**
     * Before lifecycle method for delete method.
     *
     * @param   mixed   $id
     * @param   Entity  $entity
     */
    public function beforeDelete(&$id, Entity $entity);

    /**
     * After lifecycle method for delete method.
     *
     * @param   mixed   $id
     * @param   Entity  $entity
     */
    public function afterDelete(&$id, Entity $entity);

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
     * @param   array   $ids
     * @param   array   $criteria
     * @param   array   $search
     */
    public function afterIds(array &$ids, array &$criteria, array &$search);
}
