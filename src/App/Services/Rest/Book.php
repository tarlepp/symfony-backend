<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/Book.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\Entity\Book as Entity;
use App\Repository\Book as Repository;
use Doctrine\Common\Persistence\Proxy;

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class Book
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
class Book extends Base
{
    // Implement custom service methods here
}
