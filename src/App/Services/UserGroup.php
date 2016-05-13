<?php
/**
 * /src/App/Services/UserGroup.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Application components
use App\Entity\UserGroup as Entity;
use App\Entity\Interfaces\EntityInterface as EntityInterface;
use App\Repository\UserGroup as Repository;

/**
 * Class UserGroup
 *
 * @category    Service
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Entity          getReference($id)
 * @method  Repository      getRepository()
 * @method  Entity[]        find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null, array $search = null)
 * @method  null|Entity     findOne($id)
 * @method  null|Entity     findOneBy(array $criteria, array $orderBy = null)
 * @method  Entity          create(\stdClass $data)
 * @method  Entity          save(EntityInterface $entity, $skipValidation = false)
 * @method  Entity          update($id, \stdClass $data)
 * @method  Entity          delete($id)
 */
class UserGroup extends Rest
{
    // Implement custom service methods here
}
