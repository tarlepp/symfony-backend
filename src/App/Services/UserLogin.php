<?php
/**
 * /src/App/Services/UserLogin.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

use App\Entity\Interfaces\EntityInterface as EntityInterface;
use App\Entity\UserLogin as Entity;
use App\Repository\UserLogin as Repository;

/**
 * Class UserLogin
 *
 * @category    Service
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Entity          getReference($id)
 * @method  Repository      getRepository()
 * @method  Entity[]        find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null, array $search = null)
 * @method  null|Entity     findOne($id, $throwExceptionIfNotFound = false)
 * @method  null|Entity     findOneBy(array $criteria, array $orderBy = null)
 * @method  Entity          create(\stdClass $data)
 * @method  Entity          save(EntityInterface $entity, $skipValidation = false)
 * @method  Entity          update($id, \stdClass $data)
 * @method  Entity          delete($id)
 */
class UserLogin extends Rest
{
}
