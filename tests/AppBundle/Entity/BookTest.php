<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Entity/BookTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\Book;
use App\Tests\EntityTestCase;

/**
 * Class BookTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookTest extends EntityTestCase
{
    /**
     * @var Book
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\Book';
}
