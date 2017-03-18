<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Entity/TranslationTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Entity;

use App\Entity\Translation;
use App\Tests\EntityTestCase;

/**
 * Class TranslationTest
 *
 * @package AppBundle\integration\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TranslationTest extends EntityTestCase
{
    /**
     * @var Translation
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = Translation::class;
}
