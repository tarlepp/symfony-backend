<?php
/**
 * /src/App/Controller/PlaygroundController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlaygroundController
 *
 * @Route("/playground")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class PlaygroundController extends Controller
{
    /**
     * Simple method just for testing some stuff. Feel free to play around with this!
     *
     * @Route("")
     * @Route("/")
     *
     * @Method("GET")
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function testAction(Request $request)
    {
        $content = 'This is a playground';

        return new Response($content, 200);
    }
}
