<?php
/**
 * /src/App/Controller/BookController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Symfony components
use Symfony\Component\DependencyInjection\ContainerInterface;

// 3rd party components
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class BookController
 *
 * @category    REST
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookController extends FOSRestController
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

        $this->repository = $this->getDoctrine()->getRepository('AppBundle:Book');
    }

    /**
     * todo
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBookAction()
    {
        $data = $this->repository->findAll();
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
}
