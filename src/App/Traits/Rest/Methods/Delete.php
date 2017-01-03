<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Methods/Delete.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Trait for generic 'Delete' action for REST controllers. Trait will add following route definition to your controller
 * where you use this:
 *
 *  DELETE /_your_controller_path_/_your_entity_id_
 *
 * Response of this request is deleted entity and format can be JSON or XML examples below. By default response is
 * JSON but you can change it by request headers. Responses are generated for entity that has id, name and description
 * properties. Also note that if entity does not exists you will get 404 response.
 *
 * JSON response:
 *  {
 *      "id": "60b0333b-b10e-48b7-982b-a217d031e6bb",
 *      "name": "new author",
 *      "description": "description"
 *  }
 *
 * XML response:
 *  <?xml version="1.0" encoding="UTF-8"?>
 *  <result>
 *      <id>
 *          <![CDATA[7a68f126-d46f-4c54-82c8-df71d6a3d6cf]]>
 *      </id>
 *      <name>
 *          <![CDATA[new author]]>
 *      </name>
 *      <description>
 *          <![CDATA[description]]>
 *      </description>
 *  </result>
 *
 * Note that controllers that uses this trait _must_ implement App\Controller\Interfaces\RestController interface.
 *
 * @package App\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Delete
{
    /**
     * Generic 'Delete' method for REST endpoints.
     *
     * @throws  \LogicException
     * @throws  HttpException
     * @throws  MethodNotAllowedHttpException
     *
     * @param   Request $request
     * @param   string $id
     * @param   array $allowedHttpMethods
     *
     * @return  Response
     */
    public function deleteMethod(Request $request, string $id, array $allowedHttpMethods = ['DELETE']): Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Methods\Delete trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }

        try {
            return $this->getResponseService()->createResponse($request, $this->getResourceService()->delete($id));
        } catch (\Exception $error) {
            if ($error instanceof HttpException) {
                throw $error;
            } else if ($error instanceof OptimisticLockException || $error instanceof ORMInvalidArgumentException) {
                throw new HttpException(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $error->getMessage(),
                    $error,
                    [],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            } else {
                throw new HttpException(
                    Response::HTTP_BAD_REQUEST,
                    $error->getMessage(),
                    $error,
                    [],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
    }

    /**
     * Getter method for resource service.
     *
     * @return ResourceServiceInterface
     */
    abstract public function getResourceService(): ResourceServiceInterface;

    /**
     * Getter method for REST response helper service.
     *
     * @return RestHelperResponseInterface
     */
    abstract public function getResponseService(): RestHelperResponseInterface;
}
