<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/BookTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\Book as BookEntity;
use App\Repository\Book as BookRepository;
use App\Services\Rest\Book as BookService;
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
     * @var BookService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.book';

    /**
     * @var string
     */
    protected $entityName = BookEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = BookRepository::class;
}
