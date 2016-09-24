<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Count.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Helper\Request as RestHelperRequest;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
 *  - where, see \App\Services\Rest\Helper\Request::getCriteria()
 *  - search, see \App\Services\Rest\Helper\Request::getSearchTerms()
 *
 * Note that controllers that uses this trait _must_ implement App\Controller\Interfaces\RestController interface.
 *
 * @method  RestHelperResponseInterface getResponseService()
 * @method  ResourceServiceInterface    getResourceService()
 *
 * @package App\Traits\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Count
{
    /**
     * Count action for current resource.
     *
     * @Route("/count")
     * @Route("/count/")
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function count(Request $request) : Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Count trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

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
    }
}
