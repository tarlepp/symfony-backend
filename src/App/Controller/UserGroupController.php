<?php
declare(strict_types = 1);
/**
 * /src/App/Controller/UserGroupController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Traits\Rest\Roles as RestAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class UserGroupController
 *
 * @Route(
 *      service="app.controller.user_group",
 *      path="/user_group",
 *  )
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UserGroupController extends RestController
{
    // Traits
    use RestAction\Admin\Find;
    use RestAction\Admin\FindOne;
    use RestAction\Admin\Count;
    use RestAction\Admin\Ids;
    use RestAction\Root\Create;
    use RestAction\Root\Update;
    use RestAction\Root\Delete;
}
