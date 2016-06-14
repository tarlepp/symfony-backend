<?php
/**
 * Created by PhpStorm.
 * User: wunder
 * Date: 14.6.2016
 * Time: 23:09
 */

namespace App\Tests;


use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class EntityTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        self::$kernel->shutdown();
    }

    /**
     * @dataProvider dataProviderTestThatSetterAndGettersWorks
     *
     * @param   string  $attribute
     * @param   string  $value
     */
    public function testThatSetterAndGettersWorks($attribute, $value)
    {
        $getter = 'get' . $attribute;
        $setter = 'set' . $attribute;

        call_user_func([$this->entity, $setter], $value);

        $this->assertEquals($value, call_user_func([$this->entity, $getter]));
    }
}
