<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/Admin/Create.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\Admin;

use App\Traits\Rest\Methods\Create as CreateMethod;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Trait to add 'Create' action for REST resources for 'ROLE_ADMIN' users.
 *
 * @see \App\Traits\Rest\Methods\Create for detailed documents.
 *
 * @package App\Traits\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Create
{
    use CreateMethod;

    /**
     * Create action for current resource.
     *
     * @Route("")
     * @Route("/")
     *
     * @Method({"POST"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @throws  \UnexpectedValueException
     * @throws  \LogicException
     * @throws  \InvalidArgumentException
     * @throws  OptimisticLockException
     * @throws  ORMInvalidArgumentException
     * @throws  ValidatorException
     * @throws  MethodNotAllowedHttpException
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request): Response
    {
        return $this->createMethod($request);
    }
}
