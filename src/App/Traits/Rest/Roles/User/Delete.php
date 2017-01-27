<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/User/Delete.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\User;

use App\Annotation\RestApiDoc;
use App\Traits\Rest\Methods\Delete as DeleteMethod;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Trait to add 'Delete' action for REST resources for 'ROLE_USER' users.
 *
 * @see \App\Traits\Rest\Methods\Delete for detailed documents.
 *
 * @package App\Traits\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Delete
{
    use DeleteMethod;

    /**
     * Delete action for current resource.
     *
     * @Route(
     *      "/{id}",
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
     *  )
     *
     * @Method({"DELETE"})
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @ApiDoc
     * @RestApiDoc
     *
     * @throws  \LogicException
     * @throws  MethodNotAllowedHttpException
     * @throws  HttpException
     *
     * @param   Request $request
     * @param   string  $id         Entity id as V4 uuid
     *
     * @return  Response
     */
    public function delete(Request $request, string $id): Response
    {
        return $this->deleteMethod($request, $id);
    }
}
