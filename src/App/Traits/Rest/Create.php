<?php
declare(strict_types=1);
/**
 * /src/App/Traits/Rest/Create.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Traits\Rest;

use App\Controller\Interfaces\RestController;
use App\Services\Rest\Helper\Interfaces\Response as RestHelperResponseInterface;
use App\Services\Rest\Interfaces\Base as ResourceServiceInterface;
use App\Utils\JSON;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Trait for generic 'Create' action for REST controllers. Trait will add following route definition to your controller
 * where you use this:
 *
 *  POST /_your_controller_path_
 *  POST /_your_controller_path_/
 *
 * Request must contain body that present your endpoint entity, note that only JSON is supported atm.
 *
 * Response of this request can be JSON or XML examples below. By default response is JSON but you can change it by
 * request headers. Responses are generated for entity that has id, name and description properties.
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
 * @package App\Traits\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait Create
{
    /**
     * Generic 'create' method for REST endpoints.
     *
     * @Route("")
     * @Route("/")
     *
     * @Method({"POST"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request) : Response
    {
        // Make sure that we have everything we need to make this  work
        if (!($this instanceof RestController)) {
            throw new \LogicException(
                'You cannot use App\Traits\Rest\Count trait within class that does not implement ' .
                'App\Controller\Interfaces\RestController interface.'
            );
        }

        // Determine entity / DTO data from request
        $data = JSON::decode($request->getContent());

        // Create new entity
        $entity = $this->getResourceService()->create($data);

        return $this->getResponseService()->createResponse($request, $entity, 201);
    }
}
