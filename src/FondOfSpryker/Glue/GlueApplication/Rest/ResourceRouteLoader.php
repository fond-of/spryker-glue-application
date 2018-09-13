<?php

namespace FondOfSpryker\Glue\GlueApplication\Rest;

use FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRouteCollection;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoader as BaseResourceRouteLoader;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouteLoader extends BaseResourceRouteLoader
{
    /**
     * @var \FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[] $resourcePlugins
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolver
     * @param \FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig $config
     */
    public function __construct(
        array $resourcePlugins,
        VersionResolverInterface $versionResolver,
        GlueApplicationConfig $config
    ) {
        parent::__construct($resourcePlugins, $versionResolver);

        $this->config = $config;
    }

    /**
     * @param string $resourceType
     * @param array $resources
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return array|null
     */
    public function load(string $resourceType, array $resources, Request $httpRequest): ?array
    {
        $resourceConfiguration = parent::load($resourceType, $resources, $httpRequest);

        return $this->prepareScopeOfResourceConfiguration($resourceConfiguration);
    }

    /**
     * @param array|null $resourceConfiguration
     *
     * @return array|null
     */
    protected function prepareScopeOfResourceConfiguration(?array $resourceConfiguration): ?array
    {
        if ($resourceConfiguration === null) {
            return null;
        }

        $unprotectedResourceTypes = $this->config->getUnprotectedResourceTypes();

        $currentResourceType = $resourceConfiguration[RequestConstantsInterface::ATTRIBUTE_TYPE];
        $currentControllerAction = $resourceConfiguration[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION][ResourceRouteCollection::CONTROLLER_ACTION];

        //Check if resource is unprotected
        $isUnProtected = \in_array($currentResourceType, \array_keys($unprotectedResourceTypes));

        //Check if controller action config exists
        if (!array_key_exists($currentResourceType, $unprotectedResourceTypes)) {
            return $resourceConfiguration;
        }

        //Check if action is protected, skip if resource is not configured
        if ($isUnProtected && isset($unprotectedResourceTypes[$currentResourceType])) {
            $isUnProtected = \in_array($currentControllerAction, $unprotectedResourceTypes[$currentResourceType]);
        }

        $resourceConfiguration[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION][ResourceRouteCollection::IS_PROTECTED] = !$isUnProtected;

        return $resourceConfiguration;
    }
}
