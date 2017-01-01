<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/RequestLogTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\RequestLog as Entity;
use App\Repository\RequestLog as Repository;
use App\Tests\RepositoryTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLogTest extends RepositoryTestCase
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
        'user',
    ];

    /**
     * @var bool
     */
    protected $skipUserAssociations = true;

    /**
     * @inheritdoc
     */
    protected function createEntity(EntityInterface $entity = null): EntityInterface
    {
        /** @var \App\Entity\RequestLog $entity */
        $entity = new $this->entityName(
            new Request([], [], [], [], [], ['REMOTE_ADDR' => '127.0.0.1']),
            new Response()
        );

        $entity->setMasterRequest(true);

        return parent::createEntity($entity);
    }
}
