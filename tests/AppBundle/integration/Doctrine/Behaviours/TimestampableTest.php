<?php
declare(strict_types = 1);

namespace AppBundle\integration\Doctrine\Behaviours;

use App\Entity\Author;
use App\Tests\EntityTestCase;

/**
 * Class TimestampableTest
 *
 * @package AppBundle\integration\Doctrine\Behaviours
 */
class TimestampableTest extends EntityTestCase
{
    /**
     * @var Author
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = Author::class;

    public function testThatGetCreatedAtJsonReturnsExpected()
    {
        $this->entity->setCreatedAt(new \DateTime('2016-06-20 18:00:35'));

        static::assertEquals('2016-06-20T18:00:35+00:00', $this->entity->getCreatedAtJson());

        static::assertEquals(
            new \DateTime('2016-06-20 18:00:35'),
            \DateTime::createFromFormat(\DATE_RFC3339, $this->entity->getCreatedAtJson())
        );
    }

    public function testThatGetUpdatedAtJsonReturnsExpected()
    {
        $this->entity->setUpdatedAt(new \DateTime('2016-06-20 18:00:35'));

        static::assertEquals('2016-06-20T18:00:35+00:00', $this->entity->getUpdatedAtJson());

        static::assertEquals(
            new \DateTime('2016-06-20 18:00:35'),
            \DateTime::createFromFormat(\DATE_RFC3339, $this->entity->getUpdatedAtJson())
        );
    }
}
