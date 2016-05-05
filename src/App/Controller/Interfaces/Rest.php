<?php
/**
 * /src/App/Controller/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controller\Interfaces;

// Symfony components
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null);

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
