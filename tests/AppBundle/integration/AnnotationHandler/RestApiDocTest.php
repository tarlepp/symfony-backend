<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/AnnotationHandler/RestApiDocTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\AnnotationHandler;

use App\Services\Helper\Roles;
use App\Tests\ContainerTestCase;

/**
 * Class RestApiDocTest
 *
 * @package AppBundle\integration\AnnotationHandler
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RestApiDocTest extends ContainerTestCase
{
    /**
     * @dataProvider dataProviderTestThatGenericTraitMethodsGenerateExpectedAnnotations
     *
     * @param   string  $controller
     * @param   string  $route
     * @param   int     $expectedRequirements
     * @param   string  $role
     * @param   array   $httpStatusCodes
     */
    public function testThatGenericTraitMethodsGenerateExpectedAnnotations(
        string $controller,
        string $route,
        int $expectedRequirements,
        string $role,
        array $httpStatusCodes
    ) {
        $container = $this->getContainer();
        $extractor = $container->get('nelmio_api_doc.extractor.api_doc_extractor');
        $annotation = $extractor->get($controller, $route);

        static::assertNotNull($annotation, 'Annotation was not created.');
        static::assertCount($expectedRequirements, $annotation->getRequirements(), 'Requirements are not expected');
        static::assertArrayHasKey('Authorization', $annotation->getHeaders(), 'Authorization header is not found');
        static::assertTrue(
            \in_array($role, $annotation->getAuthenticationRoles(), true),
            'Expected role is not present'
        );

        $httpStatusCodes = \array_merge($httpStatusCodes, [400, 401, 403, 405, 500]);

        \sort($httpStatusCodes);

        $statuses = \array_keys($annotation->toArray()['statusCodes']);

        static::assertSame($httpStatusCodes, $statuses, 'HTTP status codes are not expected.');
    }

    /**
     * @return array
     */
    public function dataProviderTestThatGenericTraitMethodsGenerateExpectedAnnotations(): array
    {
        return [
            [
                '\App\Controller\UserGroupController::Find',
                'app_usergroup_find',
                0,
                Roles::ROLE_ADMIN,
                [200],
            ],
            [
                '\App\Controller\UserGroupController::FindOne',
                'app_usergroup_findone',
                1,
                Roles::ROLE_ADMIN,
                [200, 404],
            ],
            [
                '\App\Controller\UserGroupController::Count',
                'app_usergroup_count',
                0,
                Roles::ROLE_ADMIN,
                [200],
            ],
            [
                '\App\Controller\UserGroupController::Ids',
                'app_usergroup_ids',
                0,
                Roles::ROLE_ADMIN,
                [200],
            ],
            [
                '\App\Controller\UserGroupController::Create',
                'app_usergroup_create',
                0,
                Roles::ROLE_ROOT,
                [201, 404],
            ],
            [
                '\App\Controller\UserGroupController::Update',
                'app_usergroup_update',
                1,
                Roles::ROLE_ROOT,
                [200, 404],
            ],
            [
                '\App\Controller\UserGroupController::Delete',
                'app_usergroup_delete',
                1,
                Roles::ROLE_ROOT,
                [200, 404],
            ],
        ];
    }
}
