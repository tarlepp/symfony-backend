<?php
/**
 * /src/App/Controller/AuthorController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Symfony components
use Symfony\Component\DependencyInjection\ContainerInterface;

// 3rd party components
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class AuthorController
 *
 * @category    REST
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorController extends FOSRestController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->repository = $this->getDoctrine()->getRepository('AppBundle:Author');
    }

    /**
     * todo
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAuthorAction()
    {
        $data = $this->repository->findAll();
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
}
