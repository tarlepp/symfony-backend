<?php
/**
 * /src/App/Controller/AuthController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Application components
use App\Entity\User;

// Symfony components
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// Sensio components
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

// 3rd party components
use JMS\Serializer\SerializationContext;

/**
 * Class AuthController
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
     * @Route("/auth/getToken")
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
     * @Route("/auth/profile");
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
        $context->setGroups(['User', 'UserGroup']);
        $context->setSerializeNull(true);

        // Create response
        $response = new Response();
        $response->setContent($this->container->get('jms_serializer')->serialize($user, 'json', $context));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
