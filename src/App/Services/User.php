<?php
/**
 * /src/App/Services/User.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Entity components
use App\Entity\User as Entity;
use App\Repository\User as Repository;

/**
 * Class User
 *
 * @category    Services
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method  Repository      getRepository()
 * @method  Entity          getReference($id)
 * @method  Entity[]        find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
 * @method  null|Entity     findOne($id)
 * @method  null|Entity     findOneBy(array $criteria, array $orderBy = null)
 * @method  Entity          create(\stdClass $data)
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
