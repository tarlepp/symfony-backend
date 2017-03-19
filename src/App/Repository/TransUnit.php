<?php
declare(strict_types=1);
/**
 * /src/App/Repository/TransUnit.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;

/**
 * Class TransUnit
 *
 * @package App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class TransUnit extends Base
{
    /**
     * Names of search columns.
     *
     * @var string[]
     */
    protected static $searchColumns = ['domain', 'key'];

    /**
     * Method to fetch translations from database for specified language and domain.
     *
     * @param   string  $language
     * @param   string  $domain
     *
     * @return  array
     */
    public function getTranslations(string $language, string $domain): array
    {
        // Create query builder
        $queryBuilder = $this->createQueryBuilder('entity');

        // Specify used parameters
        $parameters = [
            'locale' => $language,
            'domain' => $domain,
        ];

        // Build query
        $queryBuilder
            ->select(
                'entity.key             AS key',
                'translations.content   AS content'
            )
            ->innerJoin('entity.translations', 'translations')
            ->innerJoin('translations.locale', 'locale', Join::WITH, 'locale.code = :locale')
            ->andWhere('entity.domain = :domain')
            ->setParameters($parameters);

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
