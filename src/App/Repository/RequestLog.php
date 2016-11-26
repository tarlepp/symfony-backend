<?php
declare(strict_types=1);
/**
 * /src/App/Repository/RequestLog.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

/**
 * Doctrine repository class for RequestLog entities.
 *
 * @package App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class RequestLog extends Base
{
    /**
     * Helper method to clean history data from request_log table.
     *
     * @return  integer
     */
    public function cleanHistory(): int
    {
        // Determine date
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $date->sub(new \DateInterval('P3Y'));

        // Create query builder
        $queryBuilder = $this->createQueryBuilder('requestLog');

        // Define delete query
        $queryBuilder
            ->delete()
            ->where('requestLog.time < :time')
            ->setParameter('time', $date)
        ;

        // Return deleted row count
        return $queryBuilder->getQuery()->execute();
    }
}
