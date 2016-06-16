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
        $searchTerm = $this->container->get('app.services.helper.search_term');

        $content = 'This is a playground';

        $content = $searchTerm->getCriteria(['c1', 'c2'], ['dd', '', 'aa', null]);

        dump($content);
        die();

        return new Response(json_encode($content), 200);
    }
}
