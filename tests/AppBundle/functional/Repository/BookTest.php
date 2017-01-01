<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/BookTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\Book as Entity;
use App\Entity\Interfaces\EntityInterface;
use App\Repository\Book as Repository;
use App\Tests\RepositoryTestCase;

/**
 * Class AuthorTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookTest extends RepositoryTestCase
{
    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $entityName = Entity::class;

    /**
     * @var array
     */
    protected $associations = [
        'author'
    ];

    /**
     * @var array
     */
    protected $entityProperties = [];

    /**
     * @inheritdoc
     */
    protected function createEntity(EntityInterface $entity = null): EntityInterface
    {
        $this->entityProperties = [
            'title'         => 'Test Book',
            'description'   => 'Test author description',
            'releaseDate'   => new \DateTime(),
        ];

        return parent::createEntity($entity);
    }
}
