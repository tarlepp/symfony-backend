<?php
declare(strict_types = 1);
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
     * @param   Request $request
     *
     * @throws  \LogicException
     * @throws  \InvalidArgumentException
     *
     * @return  Response
     */
    public function testAction(Request $request): Response
    {
        $output = 'Hello world' . $request->getContent();

        return new Response($output, 200);
    }
}
