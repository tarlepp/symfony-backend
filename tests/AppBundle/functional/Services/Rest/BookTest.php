<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/BookTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Services\Rest\Book;
use App\Tests\RestServiceTestCase;

/**
 * Class BookTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookTest extends RestServiceTestCase
{
    /**
     * @var Book
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.book';

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\Book';

    /**
     * @var string
     */
    protected $repositoryName = 'App\Repository\Book';
}
