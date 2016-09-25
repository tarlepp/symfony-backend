<?php
/**
 * /src/App/Controller/AuthorController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Traits\Rest\Roles as RestMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AuthorController
 *
 * @Route(service="app.controller.author", path="/author")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorController extends RestController
{
    use RestMethod\User\Find;
    use RestMethod\User\FindOne;
    use RestMethod\User\Count;
    use RestMethod\Admin\Create;
    use RestMethod\Admin\Update;
    use RestMethod\Admin\Delete;
}
