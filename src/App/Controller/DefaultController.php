<?php
declare(strict_types = 1);
/**
 * /src/App/Controller/DefaultController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class DefaultController extends Controller
{
    /**
     * Default application response when requested root.
     *
     * @Route("/")
     *
     * @Method("GET");
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return new Response('', Response::HTTP_OK);
    }
}
