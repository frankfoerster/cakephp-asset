<?php

namespace FrankFoerster\Asset\Routing\Filter;

use Cake\Core\Plugin;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class AssetFilter extends \Cake\Routing\Filter\AssetFilter
{
    /**
     * Default priority for all methods in this filter
     * This filter should run before CakePHP's AssetFilter.
     *
     * @var int
     */
    protected $_priority = 8;

    /**
     * Builds asset file path based off url
     *
     * @param string $url Asset URL
     * @return string|void Absolute path for asset file
     */
    protected function _getAssetFile($url)
    {
        $parts = explode('/', $url);

        $firstPart = $parts[0];
        if ($firstPart !== 'ASSETS') {
            $pluginPart = $parts[0];
            $isAssetRequest = (isset($parts[1]) && $parts[1] === 'ASSETS');
        } else {
            $pluginPart = $parts[1];
            $isAssetRequest = true;
        }

        $namespacedPlugin = join('/', Hash::map(explode('_', $pluginPart), '{n}', function ($part) {
            return Inflector::camelize($part);
        }));
        $camelcasedPlugin = Inflector::camelize($pluginPart);

        $plugin = false;
        if (Plugin::loaded($namespacedPlugin)) {
            $plugin = $namespacedPlugin;
        } elseif (Plugin::loaded($camelcasedPlugin)) {
            $plugin = $camelcasedPlugin;
        }

        if ($plugin !== false) {
            return $this->_getPluginAsset($plugin, $parts, $isAssetRequest);
        } else {
            return $this->_getAppAsset($parts, $isAssetRequest);
        }
    }

    /**
     * Get the Assets or webroot path for the provided $plugin
     * depending on whether $isAssetRequest is true or false.
     *
     * @param string $plugin The name of the plugin.
     * @param array $parts The url split by '/'.
     * @param bool $isAssetRequest Whether the request is for an asset in /src/Assets or webroot.
     * @return string
     */
    protected function _getPluginAsset($plugin, $parts, $isAssetRequest)
    {
        $parts = array_slice($parts, $isAssetRequest ? 2 : 1);
        $fileFragment = implode(DS, $parts);
        $path = Plugin::path($plugin);
        if ($isAssetRequest) {
            $path .= 'src' . DS . 'Assets' . DS;
        } else {
            $path .= 'webroot' . DS;
        }
        return $path . $fileFragment;
    }

    /**
     * Get the Assets or webroot path for the app
     * depending on whether $isAssetRequest is true or false.
     *
     * @param array $parts The url split by '/'.
     * @param bool $isAssetRequest Whether the request is for an asset in /src/Assets or webroot.
     * @return string
     */
    protected function _getAppAsset($parts, $isAssetRequest)
    {
        $parts = array_slice($parts, $isAssetRequest ? 1 : 0);
        $fileFragment = implode(DS, $parts);
        $path = $isAssetRequest ? ROOT . DS . 'src' . DS . 'Assets' . DS : WWW_ROOT;
        return $path . $fileFragment;
    }
}
