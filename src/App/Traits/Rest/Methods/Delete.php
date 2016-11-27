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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
 * @method  RestHelperResponseInterface getResponseService()
 * @method  ResourceServiceInterface    getResourceService()
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
     *
     * @param   Request $request
     * @param   string  $id
     *
     * @return  Response
     */
    protected function deleteMethod(Request $request, string $id): Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Methods\Delete trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        return $this->getResponseService()->createResponse($request, $this->getResourceService()->delete($id));
    }
}
