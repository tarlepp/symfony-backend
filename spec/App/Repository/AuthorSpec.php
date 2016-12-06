<?php
declare(strict_types = 1);
/**
 * /spec/App/Repository/AuthorSpec.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace spec\App\Repository;

use App\Entity\Author as AuthorEntity;
use App\Entity\Interfaces\EntityInterface;
use App\Repository\Author;
use App\Repository\Interfaces\Base as RepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;

/**
 * Class AuthorSpec
 *
 * @package spec\App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorSpec extends ObjectBehavior
{
    /**
     * @param   \PhpSpec\Wrapper\Collaborator|EntityManager $entityManager
     */
    function let(
        EntityManager $entityManager
    ) {
        // Get entity class meta data
        $classMetaData = new ClassMetadata(AuthorEntity::class);

        // Mock entity manager to return created class meta data object
        $entityManager->getClassMetadata(AuthorEntity::class)->willReturn($classMetaData);

        // And assign specified constructor parameters
        $this->beConstructedWith($entityManager, $classMetaData);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Author::class);
        $this->shouldImplement(RepositoryInterface::class);
    }

    function it_should_return_expected_value_when_calling_getEntityName_method()
    {
        $this->getEntityName()->shouldBe(AuthorEntity::class);
    }

    function it_should_return_expected_value_when_calling_getAssociations_method()
    {
        $this->getAssociations()->shouldBeArray();
    }

    function it_should_return_expected_value_when_calling_getSearchColumns_method()
    {
        $expected = ['name', 'description'];

        $this->getSearchColumns()->shouldBeArray();
        $this->getSearchColumns()->shouldReturn($expected);
    }

    function it_should_return_expected_value_when_calling_getEntityManager_method()
    {
        $this->getEntityManager()->shouldReturnAnInstanceOf(EntityManager::class);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|EntityManager     $entityManager
     * @param   \PhpSpec\Wrapper\Collaborator|EntityInterface   $entity
     */
    function it_should_persist_and_flush_on_save_method(
        EntityManager $entityManager,
        EntityInterface $entity
    ) {
        $entityManager->persist($entity)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->save($entity);
    }

    /**
     * @param   \PhpSpec\Wrapper\Collaborator|EntityManager     $entityManager
     * @param   \PhpSpec\Wrapper\Collaborator|EntityInterface   $entity
     */
    function it_should_remove_and_flush_on_remove_method(
        EntityManager $entityManager,
        EntityInterface $entity
    ) {
        $entityManager->remove($entity)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->remove($entity);
    }
}
