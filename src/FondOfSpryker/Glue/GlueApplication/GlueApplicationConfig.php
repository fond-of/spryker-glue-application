<?php

namespace FondOfSpryker\Glue\GlueApplication;

use FondOfSpryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Glue\GlueApplication\GlueApplicationConfig as BaseGlueApplicationConfig;

class GlueApplicationConfig extends BaseGlueApplicationConfig
{
    /**
     * @return array
     */
    public function getUnprotectedResourceTypes(): array
    {
        return $this->get(GlueApplicationConstants::UNPROTECTED_RESOURCE_TYPES, []);
    }
}
