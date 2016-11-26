<?php
declare(strict_types = 1);
/**
 * /src/App/Controller/AuthorController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Traits\Rest\Roles as RestAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AuthorController
 *
 * @Route(
 *      service="app.controller.author",
 *      path="/author",
 *  )
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorController extends RestController
{
    use RestAction\User\Find;
    use RestAction\User\FindOne;
    use RestAction\User\Count;
    use RestAction\User\Ids;
    use RestAction\Admin\Create;
    use RestAction\Admin\Update;
    use RestAction\Admin\Delete;
}
