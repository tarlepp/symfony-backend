<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/User.php
 *
 * @User  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\Entity\Interfaces\EntityInterface as EntityInterface;
use App\Entity\User as Entity;
use App\Repository\User as Repository;

/**
 * Class User
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
class User extends Base
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
