<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Roles/User/Create.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Roles\Anon;

use App\Annotation\RestApiDoc;
use App\Traits\Rest\Methods\Create as CreateMethod;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Trait to add 'Create' action for REST resources for 'anonymous' users.
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
     *
     * @Method({"POST"})
     *
     * @ApiDoc(resource=true)
     * @RestApiDoc
     *
     * @throws  \LogicException
     * @throws  MethodNotAllowedHttpException
     * @throws  HttpException
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
