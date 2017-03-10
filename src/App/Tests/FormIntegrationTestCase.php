<?php
declare(strict_types = 1);
/**
 * /src/App/Tests/FormIntegrationTestCase.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class FormIntegrationTestCase
 *
 * @package App\Tests
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class FormIntegrationTestCase extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->getFormFactory();
    }

    /**
     * @return array
     */
    protected function getExtensions(): array
    {
        return [];
    }
}
