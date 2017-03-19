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
     * Getter method for default domain (messages) translations from database.
     *
     * @Route("/{language}.json")
     * @Route("/{domain}/{language}.json")
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
