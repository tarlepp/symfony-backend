<?php
/**
 * /src/App/Controller/SecurityController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Symfony components
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

// Sensio components
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class SecurityController
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SecurityController extends Controller
{
    /**
     * This is just for the route config, nothing else...
     *
     * @Route("/api/getToken")
     *
     * @return  Response
     */
    public function getTokenAction()
    {
        // The security layer will intercept this request
        return new Response('', 401);
    }
}
