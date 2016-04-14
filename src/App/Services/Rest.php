<?php
/**
 * /src/App/Repository/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Doctrine components
use Doctrine\ORM\EntityRepository;

/**
 * Abstract base class for all the application REST service classes.
 *
 * Base doctrine repository class for entities.
 *
 * @category    Service
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Rest
{
    /**
     * REST service entity repository.
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * Rest constructor.
     *
     * @param   EntityRepository    $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }
}
