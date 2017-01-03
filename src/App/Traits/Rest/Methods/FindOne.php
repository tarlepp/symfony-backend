<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Methods/Find.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trait for generic 'FindOne' action for REST controllers. Trait will add following route definition to your controller
 * where you use this:
 *
 *  GET /_your_controller_path_/_your_entity_id_
 *
 * Response of this request is presentation of your requested entity as in JSON or XML format depending your request
 * headers. By default response is JSON. If entity is not found from your resource service you will get 404 response.
 * Examples of responses (JSON / XML) below assuming that your resource service entity has 'id', 'name' and
 * 'description' properties.
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
trait FindOne
{
    /**
     * Generic 'FindOne' method for REST endpoints.
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
    public function findOneMethod(Request $request, string $id, array $allowedHttpMethods = ['GET']): Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Methods\FindOne trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }

        try {
            // Fetch data from database
            return $this->getResponseService()
                ->createResponse($request, $this->getResourceService()->findOne($id, true));
        } catch (\Exception $error) {
            if ($error instanceof HttpException) {
                throw $error;
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
