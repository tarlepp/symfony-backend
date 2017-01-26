<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/User/Count.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\Anon;

use App\Traits\Rest\Methods\Count as CountMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Trait to add 'Count' action for REST resources for 'anonymous' users.
 *
 * @see \App\Traits\Rest\Methods\Count for detailed documents.
 *
 * @package App\Traits\Rest\Roles\Anon
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Count
{
    use CountMethod;

    /**
     * Count action for current resource.
     *
     * @Route("/count")
     * @Route("/count/")
     *
     * @Method({"GET"})
     *
     * @throws  \LogicException
     * @throws  MethodNotAllowedHttpException
     * @throws  HttpException
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function count(Request $request): Response
    {
        return $this->countMethod($request);
    }
}
