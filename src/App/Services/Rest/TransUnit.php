<?php
declare(strict_types=1);
/**
 * /src/App/Services/Rest/TransUnit.php
 *
 * @Book  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Rest;

use App\Entity\TransUnit as Entity;
use App\Repository\TransUnit as Repository;
use Doctrine\Common\Persistence\Proxy;

// Note that these are just for the class PHPDoc block
/** @noinspection PhpHierarchyChecksInspection */
/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */

/**
 * Class TransUnit
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
class TransUnit extends Base
{
    /**
     * Method to get translations for specified language and domain.
     *
     * @param   string  $language
     * @param   string  $domain
     *
     * @return  array
     */
    public function getTranslations(string $language, string $domain): array
    {
        $output = [];

        /**
         * Lambda function to transform translation data to key-value
         *
         * @param array $data
         */
        $formatter = function (array $data) use (&$output) {
            $output[$data['key']] = $data['content'];
        };

        // Fetch translations from database and apply formatting for results
        \array_map($formatter, $this->getRepository()->getTranslations($language, $domain));

        return $output;
    }
}
