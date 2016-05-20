<?php
/**
 * /src/App/Repository/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

use App\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Composite as CompositeExpression;
use Doctrine\ORM\QueryBuilder;

/**
 * Base doctrine repository class for entities.
 *
 * @category    Doctrine
 * @package     App\Repository
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base extends EntityRepository
{
    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected $searchColumns = [];

    /**
     * Public getter method for entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return parent::getEntityManager();
    }

    /**
     * Public getter method for entity name.
     *
     * @return string
     */
    public function getEntityName()
    {
        return parent::getEntityName();
    }

    /**
     * Generic count method to determine count of entities for specified criteria and search term(s).
     *
     * @param   array       $criteria
     * @param   array|null  $search
     *
     * @return  integer
     */
    public function count(array $criteria = [], array $search = null)
    {
        // Create new query builder
        $queryBuilder = $this->createQueryBuilder('entity');

        // Process normal and search term criteria
        $this->processCriteria($queryBuilder, $criteria);
        is_null($search) ?: $this->processSearchTerms($queryBuilder, $search);

        $queryBuilder->select('COUNT(entity.id)');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

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
    ) {
        // Create new query builder
        $queryBuilder = $this->createQueryBuilder('entity');

        // Process normal and search term criteria
        $this->processCriteria($queryBuilder, $criteria);
        $this->processSearchTerms($queryBuilder, $search);

        // Process order, limit and offset
        is_null($orderBy) ?: $this->processOrderBy($queryBuilder, $orderBy);
        is_null($limit) ?: $queryBuilder->setMaxResults($limit);
        is_null($offset) ?: $queryBuilder->setFirstResult($offset);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * TODO convert findBy $criteria array to correct format !
     *
     * @param   QueryBuilder    $queryBuilder
     * @param   array           $criteria
     *
     * @return  void
     */
    protected function processCriteria(QueryBuilder $queryBuilder, array $criteria)
    {
        //$queryBuilder->where($this->getExpression($queryBuilder, $queryBuilder->expr()->andX(), $criteria));
    }

    /**
     * Helper method to process given search terms and create criteria about those. Note that each repository
     * has 'searchColumns' property which contains the fields where search term will be affected.
     *
     * @param   QueryBuilder $queryBuilder
     * @param   array        $searchTerms
     *
     * @return  void
     */
    protected function processSearchTerms(QueryBuilder $queryBuilder, array $searchTerms)
    {
        $columns = $this->searchColumns;

        if (empty($columns)) {
            return;
        }

        /**
         * Lambda function to process each search term to specified search columns.
         *
         * @param   string  $term
         *
         * @return  array
         */
        $iteratorTerm = function($term) use ($columns) {
            $iteratorColumn = function($column) use ($term) {
                return ['entity.' . $column, 'LIKE', '%' . $term . '%'];
            };

            return array_map($iteratorColumn, $columns);
        };

        // Create search criteria for each search term
        $searchCriteria = array_map($iteratorTerm, $searchTerms);

        if (count($searchCriteria)) {
            // Create used criteria array
            $criteria = [
                'or' => call_user_func_array('array_merge', $searchCriteria)
            ];

            // And attach search term condition to main query
            $queryBuilder->andWhere($this->getExpression($queryBuilder, $queryBuilder->expr()->andX(), $criteria));
        }
    }

    /**
     * Simple process method for order by part of for current query builder.
     *
     * @param   QueryBuilder    $queryBuilder
     * @param   array           $orderBy
     *
     * @return  void
     */
    protected function processOrderBy(QueryBuilder $queryBuilder, array $orderBy)
    {
        foreach ($orderBy as $column => $order) {
            $queryBuilder->addOrderBy('entity.' . $column, $order);
        }
    }

    /**
     * Recursively takes the specified criteria and adds too the expression.
     *
     * The criteria is defined in an array notation where each item in the list
     * represents a comparison <fieldName, operator, value>. The operator maps to
     * comparison methods located in ExpressionBuilder. The key in the array can
     * be used to identify grouping of comparisons.
     *
     * @example
     * $criteria = array(
     *      'or' => array(
     *          array('entity.field1', 'like', '%field1Value%'),
     *          array('entity.field2', 'like', '%field2Value%')
     *      ),
     *      'and' => array(
     *          array('entity.field3', 'eq', 3),
     *          array('entity.field4', 'eq', 'four')
     *      ),
     *      array('entity.field5', 'neq', 5)
     * );
     *
     * $qb = $this->createQueryBuilder('entity');
     * $qb->where($this->getExpression($qb, $qb->expr()->andX(), $criteria));
     * $query = $qb->getQuery();
     * echo $query->getSQL();
     *
     * // Result:
     * // SELECT *
     * // FROM tableName
     * // WHERE ((field1 LIKE '%field1Value%') OR (field2 LIKE '%field2Value%'))
     * // AND ((field3 = '3') AND (field4 = 'four'))
     * // AND (field5 <> '5')
     *
     * @see https://gist.github.com/jgornick/8671644
     *
     * @param   QueryBuilder        $queryBuilder
     * @param   CompositeExpression $expression
     * @param   array               $criteria
     *
     * @return  CompositeExpression
     */
    protected function getExpression(QueryBuilder $queryBuilder, CompositeExpression $expression, array $criteria)
    {
        if (count($criteria)) {
            foreach ($criteria as $key => $comparison) {
                if ($key === 'or') {
                    $expression->add($this->getExpression(
                        $queryBuilder,
                        $queryBuilder->expr()->orX(),
                        $comparison
                    ));
                } else if ($key === 'and') {
                    $expression->add($this->getExpression(
                        $queryBuilder,
                        $queryBuilder->expr()->andX(),
                        $comparison
                    ));
                } else {
                    list($field, $operator, $value) = $comparison;

                    $expression->add($queryBuilder->expr()->{$operator}($field, $queryBuilder->expr()->literal($value)));
                }
            }
        }

        return $expression;
    }
}
