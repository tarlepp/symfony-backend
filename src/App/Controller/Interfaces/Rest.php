<?php
/**
 * /src/App/Controller/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller\Interfaces;

// Application components
use App\Services\Rest as RestService;

// Symfony components
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface Rest
 *
 * @category    Interface
 * @package     App\Controller\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Rest
{
    /**
     * Get service.
     *
     * @return  RestService
     */
    public function getService();

    /**
     * Generic 'find' method for REST endpoints.
     *
     * @param   Request     $request
     *
     * @return  Response
     */
    public function find(Request $request);

    /**
     * Generic 'findOne' method for REST endpoints.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function findOne(Request $request, $id);

    /**
     * Generic 'create' method for REST endpoints.
     *
     * @param   Request $request
     *
     * @return  Response
     */
    public function create(Request $request);

    /**
     * Generic 'update' method for REST endpoints.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function update(Request $request, $id);

    /**
     * Generic 'delete' method for REST endpoints.
     *
     * @param   Request $request
     * @param   integer $id
     *
     * @return  Response
     */
    public function delete(Request $request, $id);
}
