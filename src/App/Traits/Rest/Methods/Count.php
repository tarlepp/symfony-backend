<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Methods/Count.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Helper\Request as RestHelperRequest;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Trait for generic 'Count' action for REST controllers. Trait will add following route definition to your controller
 * where you use this:
 *
 *  GET /_your_controller_path_/count
 *  GET /_your_controller_path_/count/
 *
 * Response of this request can be JSON or XML examples below. By default response is JSON but you can change it by
 * request headers.
 *
 * JSON response:
 *  4
 *
 * XML response:
 *  <?xml version="1.0" encoding="UTF-8"?>
 *  <result>4</result>
 *
 * Note that this API endpoint supports following query parameters:
 *  - where, see App\Services\Rest\Helper\Request::getCriteria
 *  - search, see \App\Services\Rest\Helper\Request::getSearchTerms
 *
 * Note that controllers that uses this trait _must_ implement App\Controller\Interfaces\RestController interface.
 *
 * @package App\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Count
{
    /**
     * Generic 'Count' method for REST endpoints.
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
    public function countMethod(Request $request, array $allowedHttpMethods = ['GET']): Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Methods\Count trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }

        try {
            // Determine used parameters
            $criteria   = RestHelperRequest::getCriteria($request);
            $search     = RestHelperRequest::getSearchTerms($request);

            if (method_exists($this, 'processCriteria')) {
                $this->processCriteria($criteria);
            }

            return $this->getResponseService()->createResponse(
                $request,
                $this->getResourceService()->count($criteria, $search)
            );
        } catch (\Exception $error) {
            if ($error instanceof HttpException) {
                throw $error;
            } else if ($error instanceof NoResultException) {
                throw new HttpException(Response::HTTP_NOT_FOUND, 'Not found', $error, [], Response::HTTP_NOT_FOUND);
            } else if ($error instanceof NonUniqueResultException) {
                throw new HttpException(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $error->getMessage(),
                    $error,
                    [],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
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
