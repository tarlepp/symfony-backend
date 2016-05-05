<?php
/**
 * /src/App/Controller/AuthController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Application components
use App\Services\Author;

// Sensio components
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

// Symfony components
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorController
 *
 * @Route("/author")
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorController extends Rest
{
    /**
     * Service object for controller.
     *
     * @var Author
     */
    protected $service;

    /**
     * Name of the service that controller uses. This is used on setContainer method to invoke specified service to
     * class context.
     *
     * @var string
     */
    protected $serviceName = 'app.services.author';

    /**
     * This is just for the route config, nothing else...
     *
     * @Route("/")
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function find(Request $request)
    {
        return parent::find($request);
    }

    /**
     * This is just for the route config, nothing else...
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
     * @return Response
     */
    public function findOne(Request $request, $id = 0)
    {
        return parent::findOne($request, $id);
    }
}
