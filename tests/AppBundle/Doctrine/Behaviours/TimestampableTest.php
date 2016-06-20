<?php

namespace AppBundle\Doctrine\Behaviours;

use App\Entity\Author;
use App\Tests\EntityTestCase;

class TimestampableTest extends EntityTestCase
{
    /**
     * @var Author
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityName = 'App\Entity\Author';

    public function testThatGetCreatedAtJsonReturnsExpected()
    {
        $this->entity->setCreatedAt(new \DateTime('2016-06-20 18:00:35'));

        $this->assertEquals('2016-06-20T18:00:35+00:00', $this->entity->getCreatedAtJson());

        $this->assertEquals(
            new \DateTime('2016-06-20 18:00:35'),
            \DateTime::createFromFormat(\DATE_RFC3339, $this->entity->getCreatedAtJson())
        );
    }

    public function testThatGetUpdatedAtJsonReturnsExpected()
    {
        $this->entity->setUpdatedAt(new \DateTime('2016-06-20 18:00:35'));

        $this->assertEquals('2016-06-20T18:00:35+00:00', $this->entity->getUpdatedAtJson());

        $this->assertEquals(
            new \DateTime('2016-06-20 18:00:35'),
            \DateTime::createFromFormat(\DATE_RFC3339, $this->entity->getUpdatedAtJson())
        );
    }
}
