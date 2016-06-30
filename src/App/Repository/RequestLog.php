<?php
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
    public function cleanHistory()
    {
        // Determine date
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $date->sub(new \DateInterval('P2M'));

        // Create query
        $query = $this->getEntityManager()
            ->createQuery('DELETE FROM App\Entity\RequestLog rl WHERE rl.time < :time')
            ->setParameter('time', $date->format('Y-m-d'));

        // Return deleted row count
        return $query->execute();
    }
}
