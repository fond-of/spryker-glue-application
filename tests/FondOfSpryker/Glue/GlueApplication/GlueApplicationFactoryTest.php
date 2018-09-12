<?php

namespace FondOfSpryker\Glue\GlueApplication;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\GlueApplication\Rest\ResourceRouteLoader;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\Kernel\Container;

class GlueApplicationFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\GlueApplication\GlueApplicationFactory
     */
    protected $glueApplicationFactory;

    /**
     * @var \FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @var \Spryker\Glue\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $containerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->configMock = $this->getMockBuilder(GlueApplicationConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->glueApplicationFactory = new GlueApplicationFactory();

        $this->glueApplicationFactory->setConfig($this->configMock);
        $this->glueApplicationFactory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testCreateRestResourceRouteLoader(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('offsetGet')
            ->with(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_ROUTES)
            ->willReturn([]);

        $restResourceRouteLoader = $this->glueApplicationFactory->createRestResourceRouteLoader();

        $this->assertInstanceOf(ResourceRouteLoader::class, $restResourceRouteLoader);
    }
}
