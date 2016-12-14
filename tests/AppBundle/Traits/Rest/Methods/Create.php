<?php
declare(strict_types = 1);
/**
 * /tests/AppBundle/Traits/Rest/Methods/Create.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace AppBundle\Traits\Rest\Methods;

use App\Controller\Interfaces\RestController;
use App\Traits\Rest\Methods\Create as CreateTrait;

/**
 * Class Create - just a dummy class so that we can actually test that trait.
 *
 * @package AppBundle\Traits\Rest\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Create implements RestController
{
    use CreateTrait;
}
