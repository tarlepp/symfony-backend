<?php
/**
 * /src/App/Controller/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Application components
use App\Entity\Interfaces\EntityInterface;
use App\Services\Rest as RestService;
use App\Util\JSON;

// Sensio components
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

// Symfony components
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

// FOS components
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class Rest
 *
 * This abstract class contains basic REST functionality that you can use on your own controllers.
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
     * Get service.
     *
     * @return  RestService
     */
    public function getService()
    {
        if (is_null($this->service)) {
            $this->service = $this->container->get($this->serviceName);
        }

        return $this->service;
    }

    /**
     * Generic 'find' method for REST endpoints.
     *
     * @Route("/")
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function find(Request $request)
    {
        // Determine used parameters
        $criteria   = $this->getCriteria($request);
        $orderBy    = $this->getOrderBy($request);
        $limit      = $this->getLimit($request);
        $offset     = $this->getOffset($request);
        $search     = $this->getSearchTerms($request);

        // Fetch data from database
        $data = $this->getService()->find($criteria, $orderBy, $limit, $offset, $search);

        return $this->createResponse($request, $data);
    }

    /**
     * Generic 'findOne' method for REST endpoints.
     *
     * @Route(
     *      "/{id}",
     *      requirements={"id" = "\d+"}
     *  )
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function findOne(Request $request, $id)
    {
        // Fetch data from database
        $data = $this->getService()->findOne($id, true);

        return $this->createResponse($request, $data);
    }

    /**
     * Generic 'count' method for REST endpoints.
     *
     * @Route("/count")
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function count(Request $request)
    {
        // Determine used parameters
        $criteria   = $this->getCriteria($request);
        $search     = $this->getSearchTerms($request);

        $data = [
            'count' => $this->getService()->count($criteria, $search),
        ];

        return $this->createResponse($request, $data);
    }

    /**
     * Generic 'create' method for REST endpoints.
     *
     * @Route("")
     * @Route("/")
     *
     * @Method({"POST"})
     *
     * @Security("has_role('ROLE_ADMIN')")
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
        $entity = $this->getService()->create($data);

        return $this->createResponse($request, $entity, 201);
    }

    /**
     * Generic 'update' method for REST endpoints.
     *
     * @Route(
     *      "/{id}",
     *      requirements={"id" = "\d+"}
     *  )
     *
     * @Method({"PUT"})
     *
     * @Security("has_role('ROLE_ADMIN')")
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
        $entity = $this->getService()->update($id, $data);

        return $this->createResponse($request, $entity);
    }

    /**
     * Generic 'delete' method for REST endpoints.
     *
     * @Route(
     *      "/{id}",
     *      requirements={"id" = "\d+"}
     *  )
     *
     * @Method({"DELETE"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function delete(Request $request, $id)
    {
        // Delete entity
        $entity = $this->getService()->delete($id);

        return $this->createResponse($request, $entity);
    }

    /**
     * Helper method to create actual response for request.
     *
     * @param   Request                                     $request
     * @param   mixed|EntityInterface|EntityInterface[]     $data
     * @param   integer                                     $httpStatus
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
        $populateOnly = array_key_exists('populateOnly', $request->query->all());

        // Get current entity name
        $entityName = $this->getService()->getRepository()->getEntityName();

        $bits = explode('\\', $entityName);

        // Determine used default group
        $defaultGroup = $populateAll ? 'Default' : end($bits);

        // Set all associations to be populated
        if (count($populate) === 0 && $populateAll) {
            $associations = $this->getService()->getAssociations();

            $populate = array_map('ucfirst', $associations);
        }

        if ($populateOnly) {
            $groups = count($populate) === 0 ? [$defaultGroup] : $populate;
        } else {
            $groups = array_merge([$defaultGroup], $populate);
        }

        // Create context and set used groups
        $context = new Context();
        $context->addGroups($groups);
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
        return JSON::decode($request->getContent());
    }

    /**
     * Method to get used criteria array for 'find' method.
     *
     * @param   Request $request
     *
     * @return  array
     */
    private function getCriteria(Request $request)
    {
        // TODO implement actual functionality

        return [];
    }

    /**
     * Getter method for used order by option within 'find' method. Some examples below.
     *
     * Basic usage:
     *  ?order=column1                              => ORDER BY column1 ASC
     *  ?order=-column1                             => ORDER BY column2 DESC
     *
     * Array parameter usage:
     *  ?order[column1]=ASC                         => ORDER BY column1 ASC
     *  ?order[column1]=DESC                        => ORDER BY column1 DESC
     *  ?order[column1]=foobar                      => ORDER BY column1 ASC
     *  ?order[column1]=DESC&orderBy[column2]=DESC  => ORDER BY column1 DESC, column2 DESC
     *
     * @param   Request $request
     *
     * @return  null|array
     */
    private function getOrderBy(Request $request)
    {
        // Normalize parameter value
        $userInput = array_filter((array)$request->get('order', []));

        // Initialize output
        $output = [];

        /**
         * Lambda function to process user input for 'order' parameter and convert it to proper array that
         * Doctrine repository find method can use.
         *
         * @param   string          $value
         * @param   integer|string  $key
         */
        $iterator = function(&$value, $key) use (&$output) {
            $order = 'ASC';

            if (is_string($key)) {
                $column = $key;
                $order = in_array(strtoupper($value), ['ASC', 'DESC']) ? strtoupper($value) : $order;
            } else {
                $column = $value;
            }

            if ($column[0] === '-') {
                $column = substr($column, 1);
                $order = 'DESC';
            }

            $output[$column] = $order;
        };

        // Process user input
        array_walk($userInput, $iterator);

        return count($output) > 0 ? $output : null;
    }

    /**
     * Getter method for used limit option within 'find' method.
     *
     * @param   Request $request
     *
     * @return  null|integer
     */
    private function getLimit(Request $request)
    {
        return $request->get('limit', null);
    }

    /**
     * Getter method for used offset option within 'find' method.
     *
     * @param   Request $request
     *
     * @return  null|integer
     */
    private function getOffset(Request $request)
    {
        return $request->get('offset', null);
    }

    /**
     * Getter method for used search terms within 'find' method.
     *
     * @param   Request $request
     *
     * @return  null|string[]
     */
    private function getSearchTerms(Request $request)
    {
        $search = $request->get('search', null);

        if (!is_null($search)) {
            $search = array_unique(array_filter(explode(' ', $search)));
        }

        return $search;
    }
}
