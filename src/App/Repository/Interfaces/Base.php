<?php
/**
 * /src/App/Repository/Interfaces/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository\Interfaces;

use App\Entity\Interfaces\EntityInterface;

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
    public function getEntityName();

    /**
     * Gets a reference to the entity identified by the given type and identifier without actually loading it,
     * if the entity is not yet loaded.
     *
     * @throws  \Doctrine\ORM\ORMException
     *
     * @param   integer $id
     *
     * @return  bool|\Doctrine\Common\Proxy\Proxy|null|object
     */
    public function getReference($id);

    /**
     * Gets all association mappings of the class.
     *
     * @return  array
     */
    public function getAssociations();

    /**
     * Helper method to persist specified entity to database.
     *
     * @param   EntityInterface $entity
     *
     * @return  void
     */
    public function save(EntityInterface $entity);

    /**
     * Helper method to remove specified entity from database.
     *
     * @param   EntityInterface $entity
     *
     * @return  void
     */
    public function remove(EntityInterface $entity);

    /**
     * Generic count method to determine count of entities for specified criteria and search term(s).
     *
     * @param   array       $criteria
     * @param   array|null  $search
     *
     * @return  integer
     */
    public function count(array $criteria = [], array $search = null);

    /**
     * Generic replacement for basic 'findBy' method if/when you want to use generic LIKE search.
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
        array $orderBy = null,
        $limit = null,
        $offset = null
    );
}
