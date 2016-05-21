<?php
/**
 * /src/App/Services/User.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

use App\Entity\Interfaces\EntityInterface as EntityInterface;
use App\Entity\User as Entity;
use App\Repository\User as Repository;

/**
 * Class User
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
class User extends Rest
{
    /**
     * Getter method to load user object via 'username' or 'email'.
     *
     * @param   string  $username
     *
     * @return  Entity
     */
    public function getByUsername($username)
    {
        return $this->getRepository()->loadUserByUsername($username);
    }
}
