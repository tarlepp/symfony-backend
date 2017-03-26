<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/RequestLog.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\RequestLog as Entity;
use App\Repository\RequestLog as Repository;
use Doctrine\Common\Persistence\Proxy;

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class RequestLog
 *
 * @package App\Services\Rest
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Repository      getRepository(): Repository
 * @method  Proxy|Entity    getReference(string $id): Proxy
 * @method  Entity[]        find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null): array
 * @method  null|Entity     findOne(string $id, bool $throwExceptionIfNotFound = null)
 * @method  null|Entity     findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null)
 * @method  Entity          create(\stdClass $data): Entity
 * @method  Entity          save(Entity $entity, bool $skipValidation = null): Entity
 * @method  Entity          update(string $id, \stdClass $data): Entity
 * @method  Entity          delete(string $id): Entity
 */
class RequestLog extends Base
{
    /**
     * After lifecycle method for save method.
     *
     * @throws  \Exception
     *
     * @param   EntityInterface|Entity $entity
     */
    public function afterSave(EntityInterface $entity)
    {
        parent::afterSave($entity);

        // Clean history
        $this->getRepository()->cleanHistory();
    }
}
