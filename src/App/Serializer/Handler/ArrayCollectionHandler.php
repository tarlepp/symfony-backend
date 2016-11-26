<?php
declare(strict_types = 1);
/**
 * /src/App/Serializer/Handler/ArrayCollectionHandler.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Serializer\Handler;

use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Context;
use JMS\Serializer\VisitorInterface;

/**
 * Class ArrayCollectionHandler
 *
 * @link        https://github.com/schmittjoh/JMSSerializerBundle/issues/373
 *
 * @package App\Serializer\Handler
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ArrayCollectionHandler extends \JMS\Serializer\Handler\ArrayCollectionHandler
{
    /**
     * Method to fix serialization of ArrayCollection items.
     *
     * @param   VisitorInterface    $visitor
     * @param   Collection          $collection
     * @param   array               $type
     * @param   Context             $context
     *
     * @return  mixed
     */
    public function serializeCollection(
        VisitorInterface $visitor,
        Collection $collection,
        array $type,
        Context $context
    ) {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';

        // Don't include items that will produce null elements
        $dataArray = [];

        /** @var \JMS\Serializer\SerializationContext $context */

        foreach ($collection->toArray() as $element) {
            if (!$context->isVisiting($element)) {
                $dataArray[] = $element;
            }
        }

        return $visitor->visitArray($dataArray, $type, $context);
    }
}
