<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/BookTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\Book;
use App\Tests\EntityTestCase;

/**
 * Class BookTest
 *
 * @package AppBundle\integration\Entity
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
    protected $entityName = Book::class;
}
