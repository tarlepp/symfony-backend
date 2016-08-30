<?php
/**
 * /tests/AppBundle/Services/Rest/UserGroupTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Services\Rest;

use App\Services\Rest\UserGroup;
use App\Tests\RestServiceTestCase;

/**
 * Class UserGroupTest
 *
 * @package AppBundle\Services\Rest
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
    protected $serviceName = 'app.services.rest.userGroup';

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\UserGroup';

    /**
     * @var string
     */
    protected $repositoryName = 'App\Repository\UserGroup';
}
