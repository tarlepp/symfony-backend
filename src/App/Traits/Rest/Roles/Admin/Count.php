<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/Admin/Count.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\Admin;

use App\Traits\Rest\Methods\Count as CountMethod;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Trait to add 'Count' action for REST resources for 'ROLE_ADMIN' users.
 *
 * @see \App\Traits\Rest\Methods\Count for detailed documents.
 *
 * @package App\Traits\Rest\Roles\Admin
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @throws  \UnexpectedValueException
     * @throws  \LogicException
     * @throws  \InvalidArgumentException
     * @throws  NonUniqueResultException
     * @throws  NoResultException
     * @throws  HttpException
     * @throws  MethodNotAllowedHttpException
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function count(Request $request): Response
    {
        return $this->countMethod($request);
    }
}
