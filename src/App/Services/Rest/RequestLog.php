<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/RequestLog.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\Entity\RequestLog as Entity;
use App\Entity\Interfaces\EntityInterface as EntityInterface;
use App\Repository\RequestLog as Repository;

/**
 * Class RequestLog
 *
 * @package App\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Entity          getReference($id)
 * @method  Repository      getRepository()
 * @method  Entity[]        find(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null, array $search = null)
 * @method  null|Entity     findOne($id, $throwExceptionIfNotFound = false)
 * @method  null|Entity     findOneBy(array $criteria, array $orderBy = null)
 * @method  Entity          create(\stdClass $data)
 * @method  Entity          save(EntityInterface $entity, bool $skipValidation = false)
 * @method  Entity          update($id, \stdClass $data)
 * @method  Entity          delete($id)
 */
class RequestLog extends Base
{
    // Implement custom service methods here
}
