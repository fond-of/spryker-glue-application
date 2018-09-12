<?php

namespace FondOfSpryker\Glue\GlueApplication\Rest;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig;
use ReflectionClass;
use ReflectionException;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

class ResourceRouteLoaderTest extends Unit
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]|\PHPUnit\Framework\MockObject\MockObject[]
     */
    protected $resourcePlugins;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $versionResolverMock;

    /**
     * @var \FondOfSpryker\Glue\GlueApplication\Rest\ResourceRouteLoader
     */
    protected $resourceRouteLoader;

    /**
     * @var \FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->resourcePlugins = [
            $this->getMockForAbstractClass(ResourceRoutePluginInterface::class),
        ];

        $this->versionResolverMock = $this->getMockForAbstractClass(VersionResolverInterface::class);

        $this->configMock = $this->getMockBuilder(GlueApplicationConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourceRouteLoader = new ResourceRouteLoader(
            $this->resourcePlugins,
            $this->versionResolverMock,
            $this->configMock
        );
    }

    /**
     * @return void
     */
    public function testPrepareScopeOfResourceConfiguration(): void
    {
        $unprotectedResourceTypes = [];

        $this->configMock->expects($this->atLeastOnce())
            ->method('getUnprotectedResourceTypes')
            ->willReturn($unprotectedResourceTypes);

        $resourceConfiguration = [
            RequestConstantsInterface::ATTRIBUTE_TYPE => 'tests',
            RequestConstantsInterface::ATTRIBUTE_CONFIGURATION => [
                ResourceRouteCollection::IS_PROTECTED => false,
            ],
        ];

        try {
            $reflection = new ReflectionClass(\get_class($this->resourceRouteLoader));

            $method = $reflection->getMethod('prepareScopeOfResourceConfiguration');
            $method->setAccessible(true);

            $actualResourceConfiguration = $method->invokeArgs($this->resourceRouteLoader, [
                $resourceConfiguration,
            ]);

            $this->assertTrue($actualResourceConfiguration[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION][ResourceRouteCollection::IS_PROTECTED]);
        } catch (ReflectionException $e) {
            $this->fail();
        }
    }
}
