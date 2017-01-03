<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Methods/Find.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Helper\Request as RestHelperRequest;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Trait for generic 'Find' action for REST controllers. Trait will add following route definition to your controller
 * where you use this:
 *
 *  GET /_your_controller_path_
 *  GET /_your_controller_path_/
 *
 * Response of this request is an array of your resource service entities. Response can be JSON or XML which you can
 * change with request headers, JSON output is default one. Examples below:
 *
 * JSON response:
 *  [
 *      {
 *          "id": "60b0333b-b10e-48b7-982b-a217d031e6bb",
 *          "name": "foo",
 *          "description": "description"
 *      },
 *      {
 *          "id": "60b0333b-b10e-48b7-982b-a217d031e6bb",
 *          "name": "bar",
 *          "description": "description"
 *      },
 *      ...
 *  ]
 *
 * XML response:
 *  <?xml version="1.0" encoding="UTF-8"?>
 *  <results>
 *      <entry>
 *          <id>
 *              <![CDATA[60b0333b-b10e-48b7-982b-a217d031e6bb]>
 *          </id>
 *          <name>
 *              <![CDATA[foo]]>
 *          </name>
 *          <description>
 *              <![CDATA[description]]>
 *          </description>
 *      </entry>
 *      <entry>
 *          <id>
 *              <![CDATA[60b0333b-b10e-48b7-982b-a217d031e6bb]>
 *          </id>
 *          <name>
 *              <![CDATA[bar]]>
 *          </name>
 *          <description>
 *              <![CDATA[description]]>
 *          </description>
 *      </entry>
 *      ...
 *  </results>
 *
 * Note that this API endpoint supports following query parameters:
 *  - where,        see \App\Services\Rest\Helper\Request::getCriteria()
 *  - order,        see \App\Services\Rest\Helper\Request::getOrderBy()
 *  - limit,        see \App\Services\Rest\Helper\Request::getLimit()
 *  - offset,       see \App\Services\Rest\Helper\Request::getOffset()
 *  - search,       see \App\Services\Rest\Helper\Request::getSearchTerms()
 *  - populate      see \App\Services\Rest\Helper\Response::createResponse()
 *  - populateAll   see \App\Services\Rest\Helper\Response::createResponse()
 *  - populateOnly  see \App\Services\Rest\Helper\Response::createResponse()
 *
 * Note that controllers that uses this trait _must_ implement App\Controller\Interfaces\RestController interface.
 *
 * @package App\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Find
{
    /**
     * Generic 'Find' method for REST endpoints.
     *
     * @throws  \LogicException
     * @throws  HttpException
     * @throws  MethodNotAllowedHttpException
     *
     * @param   Request $request
     * @param   array $allowedHttpMethods
     *
     * @return  Response
     */
    public function findMethod(Request $request, array $allowedHttpMethods = ['GET']): Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Methods\Find trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }

        try {
            // Determine used parameters
            $criteria   = RestHelperRequest::getCriteria($request);
            $orderBy    = RestHelperRequest::getOrderBy($request);
            $limit      = RestHelperRequest::getLimit($request);
            $offset     = RestHelperRequest::getOffset($request);
            $search     = RestHelperRequest::getSearchTerms($request);

            if (method_exists($this, 'processCriteria')) {
                $this->processCriteria($criteria);
            }

            return $this->getResponseService()->createResponse(
                $request,
                $this->getResourceService()->find($criteria, $orderBy, $limit, $offset, $search)
            );
        } catch (\Exception $error) {
            if ($error instanceof HttpException) {
                throw $error;
            } else {
                throw new HttpException(
                    Response::HTTP_BAD_REQUEST,
                    $error->getMessage(),
                    $error,
                    [],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
    }

    /**
     * Getter method for resource service.
     *
     * @return ResourceServiceInterface
     */
    abstract public function getResourceService(): ResourceServiceInterface;

    /**
     * Getter method for REST response helper service.
     *
     * @return RestHelperResponseInterface
     */
    abstract public function getResponseService(): RestHelperResponseInterface;
}
