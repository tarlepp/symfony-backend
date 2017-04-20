<?php
declare(strict_types = 1);
/**
 * /src/App/AnnotationHandler/RestApiDoc.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\AnnotationHandler;

use App\Controller\Interfaces\RestController;
use Doctrine\Common\Annotations\AnnotationReader;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\HandlerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as RouteAnnotation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * Class RestApiDoc
 *
 * @package App\AnnotationHandler
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RestApiDoc implements HandlerInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Parse route parameters in order to populate ApiDoc.
     *
     * @param   ApiDoc              $annotation
     * @param   array               $annotations
     * @param   Route               $route
     * @param   \ReflectionMethod   $method
     */
    public function handle(ApiDoc $annotation, array $annotations, Route $route, \ReflectionMethod $method)
    {
        $supported = false;

        $iterator = function ($object) use (&$supported) {
            if ($object instanceof \App\Annotation\Interfaces\RestApiDoc) {
                $supported = true;
            }
        };

        \array_map($iterator, $annotations);

        if ($supported && $this->container !== null) {
            $controllerService = $this->getControllerService($method);

            $this->process($controllerService, $annotation, $annotations, $route, $method);
        }
    }

    /**
     * Getter method for controller service resource.
     *
     * @param   \ReflectionMethod   $method
     *
     * @return  RestController|null
     */
    private function getControllerService(\ReflectionMethod $method): RestController
    {
        // Create annotation reader
        $annotationReader = new AnnotationReader();

        /** @var RouteAnnotation $controllerClass */
        $controllerClass = $annotationReader->getClassAnnotation(
            $method->getDeclaringClass(),
            RouteAnnotation::class
        );

        return $this->container->get($controllerClass->getService());
    }

    /**
     * Method to process @ApiDoc annotation changes
     *
     * @param   RestController      $controllerService
     * @param   ApiDoc              $annotation
     * @param   array               $annotations
     * @param   Route               $route
     * @param   \ReflectionMethod   $method
     */
    private function process(
        RestController $controllerService,
        ApiDoc $annotation,
        array $annotations,
        Route $route,
        \ReflectionMethod $method
    ) {
        $this->attachJwtHeader($annotation, $annotations);
        $this->attachOutput($annotation, $method, $controllerService);
        $this->attachUserRoleDocumentation($annotation, $annotations);
        $this->attachStatusCodes($annotation, $annotations, $method);
    }

    /**
     * Method adds 'Authorization' header if @Security annotation is present.
     *
     * @param   ApiDoc  $annotation
     * @param   array   $annotations
     */
    private function attachJwtHeader(ApiDoc $annotation, array $annotations)
    {
        $needsAuthorizationHeader = false;

        $iterator = function ($object) use (&$needsAuthorizationHeader) {
            if ($object instanceof Security) {
                $needsAuthorizationHeader = true;
            }
        };

        \array_map($iterator, $annotations);

        if ($needsAuthorizationHeader) {
            $annotation->addHeader(
                'Authorization',
                [
                    'description'   => 'JWT authorization key, see /auth/getToken endpoint',
                    'required'      => true,
                    'default'       => 'Bearer _token_here_'
                ]
            );
        }
    }

    /**
     * Method to attach proper output to annotation for each generic REST method.
     *
     * Note that we're using bit of hack here to set that output, because ApiDoc does not provide way to override / set
     * that after construction - and yeah this is not the correct / proper way to do this.
     *
     * @param   ApiDoc              $annotation
     * @param   \ReflectionMethod   $method
     * @param   RestController      $controllerService
     */
    private function attachOutput(ApiDoc $annotation, \ReflectionMethod $method, RestController $controllerService)
    {
        $entity = $controllerService->getResourceService()->getEntityName();
        $bits = \explode('\\', $entity);

        $output = null;

        switch ($method->getName()) {
            case 'count':
                $annotation->setResponseForStatusCode(
                    [
                        'number' => [
                            'dataType'      => 'integer',
                            'required'      => true,
                            'description'   => 'Number of resource items'
                        ]
                    ],
                    [],
                    200
                );
                break;
            case 'find':
                $output = [
                    'class'     => 'array<' . $entity . '>',
                    'groups'    => \array_slice($bits, -1, 1)
                ];
                break;
            case 'create':
            case 'delete':
            case 'findOne':
            case 'update':
                $output = [
                    'class'     => $entity,
                    'groups'    => \array_slice($bits, -1, 1)
                ];
                break;
            case 'ids':
                $annotation->setResponseForStatusCode(
                    [
                        '[]' => [
                            'dataType'      => 'array of resource id values',
                            'required'      => true,
                            'description'   => 'Resource id values as an array'
                        ]
                    ],
                    [],
                    200
                );
                break;
        }

        if ($output !== null) {
            $clazz = new \ReflectionClass(\get_class($annotation));
            $property = $clazz->getProperty('output');
            $property->setAccessible(true);
            $property->setValue($annotation, $output);
        }
    }

    /**
     * Method to attach user role data to method basic documentation.
     *
     * @param   ApiDoc  $annotation
     * @param   array   $annotations
     */
    private function attachUserRoleDocumentation(ApiDoc $annotation, array $annotations)
    {
        /** @var Security|bool $security */
        $security = false;

        /**
         * Lambda function to check if @Security annotation is present or not.
         *
         * @param $object
         */
        $iterator = function ($object) use (&$security) {
            if ($object instanceof Security) {
                $security = $object;
            }
        };

        \array_map($iterator, $annotations);

        // Oh noes, @Security annotation is not present - cannot do anything
        if ($security === false) {
            return;
        }

        // Determine necessary user role for current route
        \preg_match('/has_role\(\'(\w+)\'\)/', $security->getExpression(), $matches);

        // And we have some roles, so we can add those to method documentation
        if ($matches) {
            $message = <<<MESSAGE
%s

User has to have at least '%s' role via his/hers user groups.
MESSAGE;

            // Attach authentication roles
            $annotation->setAuthenticationRoles([$matches[1]]);

            // Attach new documentation block
            $annotation->setDocumentation(\sprintf($message, $annotation->getDocumentation(), $matches[1]));
        }
    }

    /**
     * @param   ApiDoc              $annotation
     * @param   array               $annotations
     * @param   \ReflectionMethod   $method
     */
    private function attachStatusCodes(ApiDoc $annotation, array $annotations, \ReflectionMethod $method)
    {
        // Initialize codes array
        $codes = [];

        /** @var Security|bool $security */
        $security = false;

        /**
         * Lambda function to check if @Security annotation is present or not.
         *
         * @param $object
         */
        $iterator = function ($object) use (&$security) {
            if ($object instanceof Security) {
                $security = $object;
            }
        };

        \array_map($iterator, $annotations);

        // Attach auth related status codes
        if ($security) {
            $codes[401] = 'Unauthorized';
            $codes[403] = 'Forbidden';
        }

        // Attach generic status codes
        $codes[400] = 'Bad request';
        $codes[405] = 'Method not allowed';
        $codes[500] = 'Internal server error';

        // Method specified code(s)
        switch ($method->getName()) {
            case 'count':
                $codes[200] = 'OK';
                break;
            case 'create':
                $codes[201] = 'Created';
                $codes[404] = 'Not found';
                break;
            case 'delete':
            case 'update':
            case 'findOne':
                $codes[200] = 'OK';
                $codes[404] = 'Not found';
                break;
            case 'find':
                $codes[200] = 'OK';
                break;
            case 'ids':
                $codes[200] = 'OK';
                break;
        }

        \ksort($codes);

        static $data = [
            'message'   => 'string',
            'code'      => 'integer',
            'status'    => 'integer'
        ];

        // Finally add status codes.
        foreach ($codes as $code => $description) {
            if ($code >= 400) {
                $description .= ' -  Output is an JSON object as in: ' . \json_encode($data);
            }

            $annotation->addStatusCode($code, $description);
        }
    }
}
