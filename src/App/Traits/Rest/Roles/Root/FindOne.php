<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/Root/FindOne.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\Root;

use App\Traits\Rest\Methods\FindOne as FindOneMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Trait to add 'FindOne' action for REST resources for 'ROLE_ROOT' users.
 *
 * @see \App\Traits\Rest\Methods\FindOne for detailed documents.
 *
 * @package App\Traits\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait FindOne
{
    use FindOneMethod;

    /**
     * FindOne action for current resource.
     *
     * @Route(
     *      "/{id}",
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
     *  )
     *
     * @Method({"GET"})
     *
     * @Security("has_role('ROLE_ROOT')")
     *
     * @throws  \UnexpectedValueException
     * @throws  \LogicException
     * @throws  \InvalidArgumentException
     * @throws  HttpException
     * @throws  MethodNotAllowedHttpException
     *
     * @param   Request $request
     * @param   string  $id
     *
     * @return  Response
     */
    public function findOne(Request $request, string $id): Response
    {
        return $this->findOneMethod($request, $id);
    }
}
