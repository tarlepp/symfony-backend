<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/UserGroupTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Entity\UserGroup as UserGroupEntity;
use App\Repository\UserGroup as UserGroupRepository;
use App\Services\Rest\UserGroup as UserGroupService;
use App\Tests\RestServiceTestCase;

/**
 * Class UserGroupTest
 *
 * @package AppBundle\functional\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupTest extends RestServiceTestCase
{
    /**
     * @var UserGroupService
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.user_group';

    /**
     * @var string
     */
    protected $entityName = UserGroupEntity::class;

    /**
     * @var string
     */
    protected $repositoryName = UserGroupRepository::class;

    /**
     * {@inheritdoc}
     */
    protected $entityCount = 4;
}
