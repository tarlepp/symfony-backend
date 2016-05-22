<?php
/**
 * /src/App/Controller/AuthController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Entity\User;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 *
 * @Route("/auth")
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthController extends Controller
{
    /**
     * This is just for the route config, nothing else...
     *
     * @Route("/getToken")
     *
     * @return  Response
     */
    public function getTokenAction()
    {
        // The security layer will intercept this request
        return new Response('', 401);
    }

    /**
     * Route action to return current user profile data from database.
     *
     * @Route("/profile");
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function getProfile()
    {
        /**
         * Get current user
         *
         * @var User $user
         */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        // Create serializer context
        $context = SerializationContext::create();
        $context->setGroups(['User', 'UserGroups']);
        $context->setSerializeNull(true);

        // Create response
        $response = new Response();
        $response->setContent($this->container->get('jms_serializer')->serialize($user, 'json', $context));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
