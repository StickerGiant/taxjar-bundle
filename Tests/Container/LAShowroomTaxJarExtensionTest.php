<?php

namespace LAShowroom\TaxJarBundle\Tests\Container;

use LAShowroom\TaxJarBundle\DependencyInjection\LAShowroomTaxJarExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LAShowroomTaxJarExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LAShowroomTaxJarExtension
     */
    private $extension;

    /**
     * Root name of the configuration
     *
     * @var string
     */
    private $root;

    public function setUp()
    {
        parent::setUp();

        $this->extension = $this->getExtension();
        $this->root = "la_showroom_tax_jar";
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage The child node "api_token" at path "la_showroom_tax_jar" must be configured.
     */
    public function testApiTokenRequired()
    {
        $this->extension->load([], $container = $this->getContainer());
    }

    public function testApiTokenCorrectlySet()
    {
        $this->extension->load([
            'la_showroom_tax_jar' => [
                'api_token' => 'doge',
            ]
        ], $container = $this->getContainer());

        $this->assertEquals('doge', $container->getParameter('la_showroom_tax_jar.api_token'));
    }

    /**
     * @return LAShowroomTaxJarExtension
     */
    protected function getExtension()
    {
        return new LAShowroomTaxJarExtension();
    }

    /**
     * @return ContainerBuilder
     */
    private function getContainer()
    {
        $container = new ContainerBuilder();

        return $container;
    }
}
