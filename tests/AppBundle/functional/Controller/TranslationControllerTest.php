<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/functional/Controller/TranslationControllerTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\functional\Controller;

use App\Tests\WebTestCase;

/**
 * Class TranslationControllerTest
 *
 * @package AppBundle\functional\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TranslationControllerTest extends WebTestCase
{
    static protected $baseRoute = '/translation';

    /**
     * @dataProvider dataProviderTestThatTranslationsAreReturned
     *
     * @param string $language
     */
    public function testThatTranslationsAreReturned(string $language)
    {
        $url = self::$baseRoute . '/' . $language . '.json';

        $client = self::createClient();
        $client->request('GET', $url);

        // Check that HTTP status code is correct
        static::assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            'HTTP status code was not expected for GET ' . $url . "\n" . $client->getResponse()
        );
    }

    /**
     * @return array
     */
    public function dataProviderTestThatTranslationsAreReturned(): array
    {
        return [
            ['en'],
            ['fi'],
        ];
    }
}
