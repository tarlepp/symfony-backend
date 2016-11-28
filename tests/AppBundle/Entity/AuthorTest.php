<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Entity/AuthorTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\Author;
use App\Tests\EntityTestCase;

/**
 * Class AuthorTest
 *
 * @package AppBundle\Entity
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
    protected $entityName = 'App\Entity\Author';
}
