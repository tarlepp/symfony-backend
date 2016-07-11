<?php
/**
 * /src/App/Controller/UserController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Entity\User as UserEntity;
use App\Services\Rest\User as UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UserController
 *
 * @Route("/user")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserController extends Rest
{
    /**
     * Service object for controller.
     *
     * @var UserService
     */
    protected $service;

    /**
     * Name of the service that controller uses. This is used on setContainer method to invoke specified service to
     * class context.
     *
     * @var string
     */
    protected $serviceName = 'app.services.rest.user';

    /**
     * {@inheritdoc}
     *
     * @Route("")
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
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
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
        return parent::findOne($request, $id);
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
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
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
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
     *  )
     *
     * @ParamConverter(
     *     "user",
     *     class="AppBundle:User"
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
    public function deleteUser(Request $request, UserEntity $user)
    {
        /** @var UserEntity $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($currentUser === $user) {
            throw new HttpException(400, 'You can\'t remove yourself...');
        }

        return parent::delete($request, $user->getId());
    }
}
