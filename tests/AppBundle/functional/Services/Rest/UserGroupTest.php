<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Services/Rest/UserGroupTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Services\Rest;

use App\Services\Rest\UserGroup;
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
     * @var UserGroup
     */
    protected $service;

    /**
     * @var string
     */
    protected $serviceName = 'app.services.rest.user_group';

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\UserGroup';

    /**
     * @var string
     */
    protected $repositoryName = 'App\Repository\UserGroup';

    /**
     * {@inheritdoc}
     */
    protected $entityCount = 4;
}
