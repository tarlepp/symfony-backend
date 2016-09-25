<?php
/**
 * /src/App/Controller/BookController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Traits\Rest\Roles as RestMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class BookController
 *
 * @Route(service="app.controller.book", path="/book")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookController extends RestController
{
    use RestMethod\User\Find;
    use RestMethod\User\FindOne;
    use RestMethod\User\Count;
    use RestMethod\Admin\Create;
    use RestMethod\Admin\Update;
    use RestMethod\Admin\Delete;
}
