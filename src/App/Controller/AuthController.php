<?php
/**
 * /src/App/Controller/AuthController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Entity\User;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthController
 *
 * @Route(service="app.controller.auth", path="/auth")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthController
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * AuthController constructor.
     *
     * @param   TokenStorageInterface   $tokenStorage
     * @param   SerializerInterface     $serializer
     */
    public function __construct(TokenStorageInterface $tokenStorage, SerializerInterface $serializer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->serializer = $serializer;
    }

    /**
     * Action to get user's Json Web Token (JWT) for authentication.
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Get authentication Json Web Token (JWT)",
     *      statusCodes={
     *         200="Returned when successful login",
     *         401="Returned when invalid login",
     *      },
     *      input={
     *          "class"="App\ApiDoc\Auth\GetTokenInput",
     *          "name"=""
     *      },
     *      output={
     *          "class"="App\ApiDoc\Auth\GetTokenOutput",
     *          "name"=""
     *      }
     *  )
     *
     * @Method("POST")
     *
     * @Route("/getToken")
     */
    public function getTokenAction()
    {
        // The security layer will intercept this request
    }

    /**
     * Action to get current user profile data.
     *
     * @ApiDoc(
     *      resource=true,
     *      description="Get current user profile data",
     *      headers={
     *          {
     *              "name"="Authorization",
     *              "description"="JWT authorization key",
     *              "required"=true,
     *              "default"="Bearer _token_here_",
     *          }
     *      },
     *      statusCodes={
     *          200="Returned when logged user makes request",
     *          401="Returned when using invalid credentials",
     *      },
     *      output={
     *          "class"="App\Entity\User",
     *          "groups"={"User", "UserGroups"}
     *      }
     *  )
     *
     * @Method("GET")
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
        $user = $this->tokenStorage->getToken()->getUser();

        // Create serializer context
        $context = SerializationContext::create();
        $context->setGroups(['User', 'UserGroups', 'User.createdAt', 'User.updatedAt']);
        $context->setSerializeNull(true);

        // Create response
        $response = new Response();
        $response->setContent($this->serializer->serialize($user, 'json', $context));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
