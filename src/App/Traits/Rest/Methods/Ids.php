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
 * Class Ids
 *
 * Trait for generic get get entity id values for REST controllers. Trait will add following route definition to your
 * controller where you use this.
 *
 *  GET /_your_controller_path_/ids
 *
 * Response of this request is presentation of your requested entity id values as in JSON or XML format depending your
 * request headers. By default response is JSON. Examples of responses (JSON / XML) below.
 *
 * JSON response:
 *  [
 *      "1982098b-1c82-4538-8d98-67ab7ca65dee",
 *      "5113d89d-641b-4c3e-866c-2ab21435a74b",
 *      "6a91f451-ecca-4958-b2e9-0251f1e59b14",
 *      "7696f106-1a3a-4471-9eb6-72721c2f5928",
 *      "b7d7451a-ace8-4f69-be80-e8b2ee4712b8"
 *  ]
 *
 * XML response:
 *  <?xml version="1.0" encoding="UTF-8"?>
 *  <result>
 *      <entry>
 *          <![CDATA[1982098b-1c82-4538-8d98-67ab7ca65dee]]>
 *      </entry>
 *      <entry>
 *          <![CDATA[5113d89d-641b-4c3e-866c-2ab21435a74b]]>
 *      </entry>
 *      <entry>
 *          <![CDATA[6a91f451-ecca-4958-b2e9-0251f1e59b14]]>
 *      </entry>
 *      <entry>
 *          <![CDATA[7696f106-1a3a-4471-9eb6-72721c2f5928]]>
 *      </entry>
 *      <entry>
 *          <![CDATA[b7d7451a-ace8-4f69-be80-e8b2ee4712b8]]>
 *      </entry>
 *  </result>
 *
 * Note that this API endpoint supports following query parameters:
 *  - where,        see \App\Services\Rest\Helper\Request::getCriteria()
 *  - search,       see \App\Services\Rest\Helper\Request::getSearchTerms()
 *
 * Note that controllers that uses this trait _must_ implement App\Controller\Interfaces\RestController interface.
 *
 * @package App\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Ids
{
    /**
     * Generic 'IDs' method for REST endpoints.
     *
     * @throws  \LogicException
     * @throws  \UnexpectedValueException
     * @throws  \InvalidArgumentException
     * @throws  HttpException
     * @throws  MethodNotAllowedHttpException
     *
     * @param   Request $request
     * @param   array $allowedHttpMethods
     *
     * @return  Response
     */
    public function idsMethod(Request $request, array $allowedHttpMethods = ['GET']): Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Methods\Ids trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }

        try {
            // Determine used parameters
            $criteria = RestHelperRequest::getCriteria($request);
            $search = RestHelperRequest::getSearchTerms($request);

            if (method_exists($this, 'processCriteria')) {
                $this->processCriteria($criteria);
            }

            return $this->getResponseService()->createResponse(
                $request,
                $this->getResourceService()->getIds($criteria, $search)
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
