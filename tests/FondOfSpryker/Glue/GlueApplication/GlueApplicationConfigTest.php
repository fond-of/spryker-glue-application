<?php

namespace FondOfSpryker\Glue\GlueApplication;

use Codeception\Test\Unit;
use Spryker\Shared\Config\Config;

class GlueApplicationConfigTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $glueApplicationConfig;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfsStreamDirectory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        if (!file_exists(APPLICATION_ROOT_DIR . '/config/Shared/')) {
            mkdir(APPLICATION_ROOT_DIR . '/config/Shared/', 0777, true);
        }

        $fileContent = file_get_contents(codecept_data_dir('stores.php'));
        file_put_contents(APPLICATION_ROOT_DIR . '/config/Shared/stores.php', $fileContent);

        $fileContent = file_get_contents(codecept_data_dir('empty_config_default.php'));
        file_put_contents(APPLICATION_ROOT_DIR . '/config/Shared/config_default.php', $fileContent);

        $this->glueApplicationConfig = new GlueApplicationConfig();

        Config::getInstance()->init();
    }

    /**
     * @return void
     */
    public function testGetUnprotectedResourceTypes(): void
    {
        $fileContent = file_get_contents(codecept_data_dir('config_default.php'));
        file_put_contents(APPLICATION_ROOT_DIR . '/config/Shared/config_default.php', $fileContent);

        Config::getInstance()->init();

        $expectedValue = [
            'tests' => ['getProducts'],
        ];

        $this->assertEquals($expectedValue, $this->glueApplicationConfig->getUnprotectedResourceTypes());
    }

    /**
     * @return void
     */
    public function testGetUnprotectedResourceTypesWithDefault(): void
    {
        $expectedValue = [];

        $this->assertEquals($expectedValue, $this->glueApplicationConfig->getUnprotectedResourceTypes());
    }
}
