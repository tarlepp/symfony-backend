<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/integration/Traits/Rest/Methods/Ids.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\integration\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Traits\Rest\Methods\Ids as IdsTrait;

/**
 * Class Ids - just a dummy class so that we can actually test that trait.
 *
 * @package AppBundle\integration\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Ids implements RestController
{
    use IdsTrait;
}
