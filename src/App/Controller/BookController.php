<?php
/**
 * /src/App/Controller/BookController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Services\Rest\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AuthorController
 *
 * @Route("/book")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BookController extends Rest
{
    /**
     * Service object for controller.
     *
     * @var Book
     */
    protected $service;

    /**
     * Name of the service that controller uses. This is used on setContainer method to invoke specified service to
     * class context.
     *
     * @var string
     */
    protected $serviceName = 'app.services.rest.book';
}
