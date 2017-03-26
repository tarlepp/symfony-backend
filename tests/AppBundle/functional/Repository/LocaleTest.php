<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Repository/LocaleTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Repository;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Locale as Entity;
use App\Repository\Locale as Repository;
use App\Tests\RepositoryTestCase;

/**
 * Class AuthorTest
 *
 * @package AppBundle\Entity
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class LocaleTest extends RepositoryTestCase
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
        'translations',
    ];

    /**
     * @var array
     */
    protected $entityProperties = [];

    /**
     * @inheritdoc
     */
    protected function createEntity(EntityInterface $entity = null): EntityInterface
    {
        $this->entityProperties = [
            'name'      => 'English',
            'nameShort' => 'EN',
            'code'      => 'en'
        ];

        return parent::createEntity($entity);
    }
}
