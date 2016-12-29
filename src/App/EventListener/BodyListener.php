<?php
declare(strict_types=1);
/**
 * /src/App/EventListener/BodyListener.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\EventListener;

use App\Utils\JSON;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class BodyListener
 *
 * @see /app/config/services_listeners.yml
 *
 * @package App\EventListener
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class BodyListener
{
    /**
     * Implementation of BodyListener event. Purpose of this is to convert JSON request data to proper request
     * parameters.
     *
     * @throws  \LogicException
     *
     * @param   GetResponseEvent $event
     *
     * @return  void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        // Get current request
        $request = $event->getRequest();

        // Request content is empty so assume that it's ok - probably DELETE or OPTION request
        if (empty($request->getContent())) {
            return;
        }

        // If request is JSON type convert it to request parameters
        if ($this->isJsonRequest($request)) {
            $this->transformJsonBody($request);
        }
    }

    /**
     * Method to determine if current Request is JSON type or not.
     *
     * @param   Request $request
     *
     * @return  bool
     */
    private function isJsonRequest(Request $request): bool
    {
        return in_array($request->getContentType(), ['', 'json', 'txt'], true);
    }

    /**
     * Method to transform JSON type request to proper request parameters.
     *
     * @throws  \LogicException
     *
     * @param   Request $request
     *
     * @return  void
     */
    private function transformJsonBody(Request $request)
    {
        $data = JSON::decode($request->getContent(), true);

        $request->request->replace($data);
    }
}
