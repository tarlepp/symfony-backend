<?php
/**
 * /src/App/Services/Helper/SearchTerm.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services\Helper;

/**
 * Class SearchTerm
 *
 * @package App\Services\Helper
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SearchTerm
{
    const OPERAND_OR = 'or';
    const OPERAND_AND = 'and';

    const MODE_STARTS_WITH = 1;
    const MODE_ENDS_WITH = 2;
    const MODE_FULL = 3;

    /**
     * @param   string|array    $column     Search column(s), could be a string or an array of strings.
     * @param   string|array    $search     Search term(s), could be a string or an array of strings.
     * @param   string          $operand    Used operand with multiple search terms. See OPERAND_* constants.
     * @param   integer         $mode       Used mode on LIKE search. See MODE_* constants.
     *
     * @return  array|null
     */
    public function getCriteria($column, $search, $operand = self::OPERAND_OR, $mode = self::MODE_FULL)
    {
        /**
         * Lambda function to filter out all "empty" values.
         *
         * @param   mixed   $value
         *
         * @return  bool
         */
        $iterator = function ($value) {
            return mb_strlen(trim((string)$value)) > 0;
        };

        // Normalize column and search parameters
        $columns = array_filter(array_map('trim', (is_array($column) ? $column : [$column])), $iterator);
        $searchTerms = array_unique(
            array_filter(array_map('trim', (is_array($search) ? $search : explode(' ', $search))), $iterator)
        );

        /**
         * Lambda function to process each search term to specified search columns.
         *
         * @param   string  $term
         *
         * @return  array
         */
        $iteratorTerm = function ($term) use ($columns, $mode) {
            $iteratorColumn = function ($column) use ($term, $mode) {
                if (strpos($column, '.') === false) {
                    $column = 'entity.' . $column;
                }

                switch ($mode) {
                    case self::MODE_STARTS_WITH:
                        $term = $term . '%';
                        break;
                    case self::MODE_ENDS_WITH:
                        $term = '%' . $term;
                        break;
                    case self::MODE_FULL:
                    default:
                        $term = '%' . $term . '%';
                        break;
                }

                return [$column, 'like', $term];
            };

            return count($columns) ? array_map($iteratorColumn, $columns) : null;
        };

        // Get criteria
        $criteria = array_filter(array_map($iteratorTerm, $searchTerms));

        // Initialize output
        $output = null;

        // We have some generated criteria
        if (count($criteria)) {
            // Create used criteria array
            $output = [
                'and' => [
                    $operand => call_user_func_array('array_merge', $criteria)
                ]
            ];
        }

        return $output;
    }
}
