<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/AuthorTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\Author;
use App\Tests\EntityTestCase;

/**
 * Class AuthorTest
 *
 * @package AppBundle\integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorTest extends EntityTestCase
{
    /**
     * @var Author
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = Author::class;
}
