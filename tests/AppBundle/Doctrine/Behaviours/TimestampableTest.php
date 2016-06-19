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
        $this->entity->setCreatedAt(new \DateTime());

        $this->assertInstanceOf('\DateTime', $this->entity->getCreatedAt());
        $this->assertEquals('UTC', $this->entity->getCreatedAt()->getTimezone()->getName());
    }

    public function testThatGetUpdatedAtJsonReturnsExpected()
    {
        $this->entity->setUpdatedAt(new \DateTime());

        $this->assertInstanceOf('\DateTime', $this->entity->getUpdatedAt());
        $this->assertEquals('UTC', $this->entity->getUpdatedAt()->getTimezone()->getName());
    }
}
