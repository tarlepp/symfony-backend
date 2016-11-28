<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Entity/RequestLogTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Entity;

use App\Entity\RequestLog;
use App\Tests\EntityTestCase;

/**
 * Class RequestLogTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLogTest extends EntityTestCase
{
    /**
     * @var RequestLog
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\RequestLog';
}
