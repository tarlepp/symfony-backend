<?php
declare(strict_types = 1);
/**
 * /src/App/Annotation/RestApiDoc.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class RestApiDoc
 *
 * @Annotation
 * @Annotation\Target("METHOD")
 *
 * @package App\Annotation
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RestApiDoc implements Interfaces\RestApiDoc
{
}
