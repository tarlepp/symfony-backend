<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/LocaleTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\Locale;
use App\Tests\EntityTestCase;

/**
 * Class LocaleTest
 *
 * @package AppBundle\integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class LocaleTest extends EntityTestCase
{
    /**
     * @var Locale
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = Locale::class;
}
