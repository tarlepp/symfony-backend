<?php
/**
 * /src/App/Controller/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Application components
use App\Services\Rest as RestService;

// Symfony components
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// 3rd party components
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class Rest
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Rest extends FOSRestController implements Interfaces\Rest
{
    /**
     * Service object for controller.
     *
     * @var RestService
     */
    protected $service;

    /**
     * Name of the service that controller uses. This is used on setContainer method to invoke specified service to
     * class context.
     *
     * @var string
     */
    protected $serviceName;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        // Set used service
        $this->service = $this->container->get($this->serviceName);
    }

    /**
     * Generic 'find' method for REST endpoints.
     *
     * @todo How to handle where criteria?
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function find(Request $request)
    {
        // Fetch data from database
        $data = $this->service->find();

        return $this->createResponse($request, $data);
    }

    /**
     * Generic 'findOne' method for REST endpoints.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function findOne(Request $request, $id)
    {
        // Fetch data from database
        $data = $this->service->findOne($id);

        // Oh noes, record not found...
        if (is_null($data)) {
            throw $this->createNotFoundException('Record does\'t exists.');
        }

        return $this->createResponse($request, $data);
    }

    /**
     * Generic 'create' method for REST endpoints.
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request)
    {
        // Determine entity data from request
        $data = $this->getEntityData($request);

        // Create new entity
        $entity = $this->service->create($data);

        return $this->createResponse($request, $entity, 201);
    }

    /**
     * Generic 'update' method for REST endpoints.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function update(Request $request, $id)
    {
        // Determine entity data from request
        $data = $this->getEntityData($request);

        // Update entity
        $entity = $this->service->update($id, $data);

        return $this->createResponse($request, $entity);
    }

    /**
     * Generic 'delete' method for REST endpoints.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function delete(Request $request, $id)
    {
        // Remove entity
        $entity = $this->service->delete($id);

        return $this->createResponse($request, $entity);
    }

    /**
     * Helper method to create actual response for request.
     *
     * @param   Request $request
     * @param   mixed   $data
     * @param   integer $httpStatus
     *
     * @return  Response
     */
    protected function createResponse(Request $request, $data, $httpStatus = 200)
    {
        // Determine used context
        $context = $this->getSerializeContext($request);

        // Create new view
        $view = $this->view($data, $httpStatus);
        $view->setContext($context);
        $view->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * Helper method to get serialization context for query.
     *
     * @param   Request $request
     *
     * @return  Context
     */
    protected function getSerializeContext(Request $request)
    {
        // Specify used populate settings
        $populate = (array)$request->get('populate', []);
        $populateAll = array_key_exists('populateAll', $request->query->all());

        // Get current entity name
        $entityName = $this->service->getRepository()->getClassName();

        $bits = explode('\\', $entityName);

        // Determine used default group
        $defaultGroup = $populateAll ? 'Default' : end($bits);

        // Set all associations to be populated
        if (count($populate) === 0 && $populateAll) {
            $associations = array_keys(
                $this->getDoctrine()->getManager()->getClassMetadata($entityName)->getAssociationMappings()
            );

            $populate = array_map('ucfirst', $associations);
        }

        // Create context and set used groups
        $context = new Context();
        $context->addGroups(array_merge([$defaultGroup], $populate));
        $context->setSerializeNull(true);

        return $context;
    }

    /**
     * Helper method to parse current request to entity data.
     *
     * @param   Request $request
     *
     * @return  \stdClass
     */
    protected function getEntityData(Request $request)
    {
        // TODO handle this!
        return new \stdClass();
    }
}
