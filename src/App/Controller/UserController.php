<?php
/**
 * /src/App/Controller/UserController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Services\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 *
 * @Route("/user")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserController extends Rest
{
    /**
     * Service object for controller.
     *
     * @var User
     */
    protected $service;

    /**
     * Name of the service that controller uses. This is used on setContainer method to invoke specified service to
     * class context.
     *
     * @var string
     */
    protected $serviceName = 'app.services.user';

    /**
     * {@inheritdoc}
     *
     * @Route("/")
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function find(Request $request)
    {
        return parent::find($request);
    }

    /**
     * {@inheritdoc}
     *
     * @Route(
     *      "/{id}",
     *      requirements={"id" = "\d+"}
     *  )
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function findOne(Request $request, $id)
    {
        parent::findOne($request, $id);
    }

    /**
     * {@inheritdoc}
     *
     * @Route("/count")
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function count(Request $request)
    {
        return parent::count($request);
    }

    /**
     * {@inheritdoc}
     *
     * @Route("")
     * @Route("/")
     *
     * @Method({"POST"})
     *
     * @Security("has_role('ROLE_ROOT')")
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request)
    {
        return parent::create($request);
    }

    /**
     * {@inheritdoc}
     *
     * @Route(
     *      "/{id}",
     *      requirements={"id" = "\d+"}
     *  )
     *
     * @Method({"PUT"})
     *
     * @Security("has_role('ROLE_ROOT')")
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }

    /**
     * {@inheritdoc}
     *
     * @Route(
     *      "/{id}",
     *      requirements={"id" = "\d+"}
     *  )
     *
     * @Method({"DELETE"})
     *
     * @Security("has_role('ROLE_ROOT')")
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function delete(Request $request, $id)
    {
        return parent::delete($request, $id);
    }
}
