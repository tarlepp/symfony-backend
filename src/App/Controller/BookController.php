<?php
declare(strict_types = 1);
/**
 * /src/App/Controller/BookController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Traits\Rest\Roles as RestAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class BookController
 *
 * @Route(
 *      service="app.controller.book",
 *      path="/book",
 *  )
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookController extends RestController
{
    use RestAction\User\Find;
    use RestAction\User\FindOne;
    use RestAction\User\Count;
    use RestAction\User\Ids;
    use RestAction\Admin\Create;
    use RestAction\Admin\Update;
    use RestAction\Admin\Delete;
}
