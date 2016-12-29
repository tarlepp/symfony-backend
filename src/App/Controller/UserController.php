<?php
declare(strict_types=1);
/**
 * /src/App/Controller/UserController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Entity\User as UserEntity;
use App\Traits\Rest\Methods as RestMethod;
use App\Traits\Rest\Roles as RestAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserController
 *
 * @Route(
 *      service="app.controller.user",
 *      path="/user",
 *  )
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserController extends RestController
{
    // Traits
    use RestAction\Admin\Find;
    use RestAction\Admin\FindOne;
    use RestAction\Admin\Count;
    use RestAction\Admin\Ids;
    use RestAction\Root\Create;
    use RestAction\Root\Update;
    use RestMethod\Delete;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * Setter for token storage.
     *
     * @param   TokenStorageInterface   $tokenStorage
     *
     * @return  UserController
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): UserController
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
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
     * @throws  \LogicException
     * @throws  MethodNotAllowedHttpException
     * @throws  HttpException
     *
     * @param   Request $request
     * @param   UserEntity $user
     *
     * @return  Response
     */
    public function delete(Request $request, UserEntity $user): Response
    {
        /** @var UserEntity $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();

        if ($currentUser === $user) {
            throw new HttpException(400, 'You can\'t remove yourself...');
        }

        return $this->deleteMethod($request, $user->getId());
    }
}
