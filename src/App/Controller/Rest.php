<?php
/**
 * /src/App/Controller/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Entity\Interfaces\EntityInterface;
use App\Services\Rest as RestService;
use App\Utils\JSON;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @Route("")
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

        if (method_exists($this, 'processCriteria')) {
            $this->processCriteria($criteria);
        }

        // Fetch data from database
        $data = $this->getService()->find($criteria, $orderBy, $limit, $offset, $search);

        return $this->createResponse($request, $data);
    }

    /**
     * Generic 'findOne' method for REST endpoints.
     *
     * @Route(
     *      "/{id}",
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
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

        if (method_exists($this, 'processCriteria')) {
            $this->processCriteria($criteria);
        }

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
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
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
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
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
     * Method to process current 'criteria' array before 'find' and 'count' methods. Actual criteria is an array which
     * is parsed parsed from request ?where parameter.
     *
     * This is usefully when you need to make custom query criteria for your REST find/count query.
     *
     * @see \App\Repository\Base::processCriteria
     *
     * @param   array   $criteria
     *
     * @return  void
     */
    public function processCriteria(array &$criteria)
    {
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
        $entityName = $this->getService()->getEntityName();

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
        try {
            $userInput = array_filter(JSON::decode($request->get('where', '{}'), true));
        } catch (\LogicException $error) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Current \'where\' parameter is not valid JSON.');
        }

        return $userInput;
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
        $iterator = function (&$value, $key) use (&$output) {
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
     * @todo improve docs about different use cases.
     *
     * @see App\Repository\Base::processSearchTerms
     *
     * @param   Request $request
     *
     * @return  null|string[]
     */
    private function getSearchTerms(Request $request)
    {
        $search = $request->get('search', null);

        if (!is_null($search)) {
            try {
                $input = JSON::decode($search, true);

                if (!array_key_exists('and', $input) && !array_key_exists('or', $input)) {
                    throw new HttpException(
                        Response::HTTP_BAD_REQUEST,
                        'Given search parameter is not valid, within JSON provide \'and\' and/or \'or\' property.'
                    );
                }

                /**
                 * Lambda function to normalize JSON search terms.
                 *
                 * @param   string|array $terms
                 */
                $iterator = function (&$terms) {
                    if (!is_array($terms)) {
                        $terms = explode(' ', (string)$terms);
                    }

                    $terms = array_unique(array_filter($terms));
                };

                // Normalize user input, note that this support array and string formats on value
                array_walk($input, $iterator);

                $search = $input;
            } catch (\LogicException $error) {
                // By default we want to use 'OR' operand with given search words.
                $search = [
                    'or' => array_unique(array_filter(explode(' ', $search)))
                ];
            }
        }

        return $search;
    }
}
