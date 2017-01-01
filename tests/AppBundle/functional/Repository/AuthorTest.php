<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/AuthorTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\Author as Entity;
use App\Repository\Author as Repository;
use App\Tests\RepositoryTestCase;

/**
 * Class AuthorTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorTest extends RepositoryTestCase
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
        'books'
    ];

    /**
     * @var array
     */
    protected $entityProperties = [
        'name'          => 'Test author',
        'description'   => 'Test author description',
    ];
}
