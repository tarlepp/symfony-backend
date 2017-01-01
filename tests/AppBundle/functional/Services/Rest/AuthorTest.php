<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/AuthorTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\Author as AuthorEntity;
use App\Repository\Author as AuthorRepository;
use App\Services\Rest\Author as AuthorService;
use App\Tests\RestServiceTestCase;

/**
 * Class AuthorTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorTest extends RestServiceTestCase
{
    /**
     * @var AuthorService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.author';

    /**
     * @var string
     */
    protected $entityName = AuthorEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = AuthorRepository::class;
}
