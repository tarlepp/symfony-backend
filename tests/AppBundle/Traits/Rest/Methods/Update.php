<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/Update.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Traits\Rest\Methods\Update as UpdateTrait;

/**
 * Class Update - just a dummy class so that we can actually test that trait.
 *
 * @package AppBundle\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Update implements RestController
{
    use UpdateTrait;
}
