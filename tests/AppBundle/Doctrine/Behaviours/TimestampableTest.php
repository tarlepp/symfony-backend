<?php
/**
 * Created by PhpStorm.
 * User: wunder
 * Date: 19.6.2016
 * Time: 21:28
 */

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
    protected $entityName = 'App\Entity\Book';

    public function setUp()
    {
        parent::setUp();

        $this->entity->setCreatedAt(new \DateTime());
        $this->entity->setUpdatedAt(new \DateTime());
    }

    public function testThatGetCreatedAtJsonReturnsExpected()
    {
        $this->assertInstanceOf('\DateTime', $this->entity->getCreatedAt());
        $this->assertEquals('UTC', $this->entity->getCreatedAt()->getTimezone()->getName());
    }

    public function testThatGetUpdatedAtJsonReturnsExpected()
    {
        $this->assertInstanceOf('\DateTime', $this->entity->getUpdatedAt());
        $this->assertEquals('UTC', $this->entity->getUpdatedAt()->getTimezone()->getName());
    }
}
