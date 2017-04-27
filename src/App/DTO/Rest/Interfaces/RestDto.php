<?php
declare(strict_types = 1);
/**
 * /src/App/DTO/Rest/Interfaces/RestDto.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\DTO\Rest\Interfaces;

use App\Entity\Interfaces\EntityInterface;

/**
 * Interface RestDto
 *
 * @package App\DTO\Rest\Interfaces
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface RestDto
{
    /**
     * Getter method for visited setters. This is needed for dto patching.
     *
     * @return array
     */
    public function getVisited(): array;

    /**
     * Method to patch current dto with another one.
     *
     * @throws  \BadMethodCallException
     *
     * @param   RestDto $dto
     *
     * @return  RestDto
     */
    public function patch(RestDto $dto): RestDto;

    /**
     * Method to load DTO data from specified entity.
     *
     * @param   EntityInterface $entity
     *
     * @return  RestDto
     */
    public function load(EntityInterface $entity): RestDto;

    /**
     * Method to update specified entity with DTO data.
     *
     * @param   EntityInterface  $entity
     *
     * @return  EntityInterface
     */
    public function update(EntityInterface $entity): EntityInterface;
}