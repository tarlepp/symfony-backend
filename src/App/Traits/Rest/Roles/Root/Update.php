<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/Root/Update.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\Root;

use App\Traits\Rest\Methods\Update as UpdateMethod;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Trait to add 'Update' action for REST resources for 'ROLE_ROOT' users.
 *
 * @see \App\Traits\Rest\Methods\Update for detailed documents.
 *
 * @package App\Traits\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Update
{
    use UpdateMethod;

    /**
     * Update action for current resource.
     *
     * @Route(
     *      "/{id}",
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
     *  )
     *
     * @Method({"PUT"})
     *
     * @Security("has_role('ROLE_ROOT')")
     *
     * @throws  \InvalidArgumentException
     * @throws  \LogicException
     * @throws  \UnexpectedValueException
     * @throws  OptimisticLockException
     * @throws  ORMInvalidArgumentException
     * @throws  HttpException
     * @throws  MethodNotAllowedHttpException
     * @throws  ValidatorException
     *
     * @param   Request $request
     * @param   string  $id
     *
     * @return  Response
     */
    public function update(Request $request, string $id): Response
    {
        return $this->updateMethod($request, $id);
    }
}
