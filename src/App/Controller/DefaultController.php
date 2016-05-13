<?php
/**
 * /src/App/Controller/DefaultController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Sensio components
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

// Symfony components
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DefaultController extends Controller
{
    /**
     * Default application response when requested root.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        return new Response('', Response::HTTP_OK);
    }
}
