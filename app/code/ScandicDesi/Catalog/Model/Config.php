<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 07/09/17
 * Time: 11:19 PM
 */

namespace ScandicDesi\Catalog\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const MODULE_NAME = 'ScandicDesi_Catalog';

    /** @var ModuleManager */
    private $moduleManager;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ModuleManager $moduleManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ModuleManager $moduleManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->moduleManager = $moduleManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $isModuleEnabled = $this->moduleManager->isEnabled(self::MODULE_NAME);
        $isConfigEnabled = $this->getConfigValue('enable');
        return $isConfigEnabled && $isModuleEnabled;
    }

    /**
     * Return the modules store config values
     *
     * @param string $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @return mixed
     */
    public function getConfigValue(
        $field = '',
        $group = 'general',
        $section = 'scandicdesi_catalog',
        $scope = ScopeInterface::SCOPE_WEBSITES
    ) {
        return $this->scopeConfig->getValue("{$section}/{$group}/{$field}", $scope);
    }
}