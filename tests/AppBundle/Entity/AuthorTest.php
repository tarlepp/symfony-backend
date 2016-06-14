<?php
namespace AppBundle\Entity;

use App\Entity\Author;
use App\Tests\EntityTestCase;

class AuthorTest extends EntityTestCase
{
    /**
     * @var Author
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->entity = new Author();
    }

    /**
     * Data provider for 'testThatSetterAndGettersWorks'
     *
     * @return array
     */
    public function dataProviderTestThatSetterAndGettersWorks()
    {
        return [
            ['Name', 'John Don'],
            ['Description', 'Some description'],
        ];
    }
}
