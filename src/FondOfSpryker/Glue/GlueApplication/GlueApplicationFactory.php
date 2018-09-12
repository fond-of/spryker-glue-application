<?php

namespace FondOfSpryker\Glue\GlueApplication;

use FondOfSpryker\Glue\GlueApplication\Rest\ResourceRouteLoader;
use Spryker\Glue\GlueApplication\GlueApplicationFactory as BaseGlueApplicationFactory;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;

/**
 * @method \FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 */
class GlueApplicationFactory extends BaseGlueApplicationFactory
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    public function createRestResourceRouteLoader(): ResourceRouteLoaderInterface
    {
        return new ResourceRouteLoader(
            $this->getResourceRoutePlugins(),
            $this->createRestVersionResolver(),
            $this->getConfig()
        );
    }
}
