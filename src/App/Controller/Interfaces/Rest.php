<?php
/**
 * /src/App/Controller/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller\Interfaces;

use App\Services\Rest as RestService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface Rest
 *
 * @category    Interface
 * @package     App\Controller\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Rest
{
    /**
     * Get service.
     *
     * @return  RestService
     */
    public function getService();

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
    public function find(Request $request);

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
    public function findOne(Request $request, $id);

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
    public function create(Request $request);

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
    public function update(Request $request, $id);

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
    public function delete(Request $request, $id);

    /**
     * Method to process current 'criteria' array before 'find' and 'count' methods. Actual criteria is an array which
     * is parsed parsed from request ?where parameter.
     *
     * @param   array   $criteria
     *
     * @return  void
     */
    public function processCriteria(array &$criteria);
}
