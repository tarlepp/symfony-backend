<?php
/**
 * /src/App/Controller/AuthorController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Symfony components
use FOS\RestBundle\Context\Context;
use Symfony\Component\DependencyInjection\ContainerInterface;

// 3rd party components
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAuthorAction(Request $request)
    {
        $populate = (array)$request->get('populate', []);
        $populateAll = array_key_exists('populateAll', $request->query->all());

        $context = $this->getSerializeContext($populate, $populateAll);

        $data = $this->repository->findAll();
        $view = $this->view($data, 200);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * Helper method to get serialization context for query.
     *
     * @param   array   $populate
     * @param   boolean $populateAll
     *
     * @return  Context
     */
    protected function getSerializeContext(array $populate, $populateAll)
    {
        $bits = explode('\\', $this->repository->getClassName());

        // Determine used default group
        $defaultGroup = $populateAll ? 'Default' : end($bits);

        if (count($populate) === 0 && $populateAll) {
            $associations = array_keys(
                $this->getDoctrine()->getManager()->getClassMetadata('AppBundle:Author')->getAssociationMappings()
            );

            $populate = array_map('ucfirst', $associations);
        }

        // Create context and set used groups
        $context = new Context();
        $context->addGroups(array_merge([$defaultGroup], $populate));
        $context->setSerializeNull(true);

        return $context;
    }
}
