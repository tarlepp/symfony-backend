<?php
/**
 * /src/App/Controller/AuthorController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

// Application components
use App\Services\Author;

// Sensio components
use /** @noinspection PhpUnusedAliasInspection */ Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use /** @noinspection PhpUnusedAliasInspection */ Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use /** @noinspection PhpUnusedAliasInspection */ Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

// Symfony components
use /** @noinspection PhpUnusedAliasInspection */ Symfony\Component\HttpFoundation\Response;
use /** @noinspection PhpUnusedAliasInspection */ Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorController
 *
 * @Route("/author")
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 *
 * @category    Controller
 * @package     App\Controller
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthorController extends Rest
{
    /**
     * Service object for controller.
     *
     * @var Author
     */
    protected $service;

    /**
     * Name of the service that controller uses. This is used on setContainer method to invoke specified service to
     * class context.
     *
     * @var string
     */
    protected $serviceName = 'app.services.author';
}
