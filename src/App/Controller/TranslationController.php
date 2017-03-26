<?php
declare(strict_types = 1);
/**
 * /src/App/Controller/TranslationController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller;

use App\Services\Rest\TransUnit as ResourceService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class TranslationController
 *
 * @Route(
 *      service="app.controller.translation",
 *      path="/translation",
 *  )
 *
 * @package App\Controller
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  ResourceService getResourceService()
 */
class TranslationController extends RestController
{
    /**
     * Controller action method to fetch currently supported locales from database.
     *
     * @Route("/locales.json")
     *
     * @Method("GET")
     *
     * @param   Request $request
     *
     * @return  Response
     *
     * @throws  \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getLocales(Request $request)
    {
        return $this->getResponseService()->createResponse(
            $request,
            $this->getResourceService()->getRepository()->getLocales()
        );
    }

    /**
     * Getter method for default domain (messages) translations from database. Note that this covers following requests
     *  - GET /translation/en.json
     *  - GET /translation/domain/en.json
     *  - GET /translation/domain/domain2/en.json
     *  - GET /translation/domain/domain2/domain3/en.json
     *  - etc.
     *
     * @see https://symfony.com/doc/current/routing/slash_in_parameter.html
     *
     * @Route("/{language}.json")
     * @Route("/{domain}/{language}.json", requirements={"domain"=".+"})
     *
     * @Method("GET")
     *
     * @param   Request $request
     * @param   string  $language
     * @param   string  $domain
     *
     * @return  Response
     *
     * @throws  \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getTranslation(Request $request, string $domain = null, string $language = null): Response
    {
        $language = $language ?? 'en';
        $domain = $domain ?? 'messages';

        // Fetch translation data
        $translations = $this->getResourceService()->getTranslations($language, $domain);

        return $this->getResponseService()->createResponse($request, $translations);
    }
}
