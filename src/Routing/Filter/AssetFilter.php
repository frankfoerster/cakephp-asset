<?php
/**
 * Copyright (c) Frank Förster (http://frankfoerster.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Frank Förster (http://frankfoerster.com)
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace FrankFoerster\Asset\Routing\Filter;

use Cake\Core\Configure;
use Cake\Core\Plugin;
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
     * Builds asset file path based on the provided $url.
     *
     * @param string $url Asset URL
     * @return string|void Absolute path for asset file
     */
    protected function _getAssetFile($url)
    {
        $parts = explode('/', $url);
        $pluginPart = [];
        $plugin = false;
        for ($i = 0; $i < 2; $i++) {
            if (!isset($parts[$i])) {
                break;
            }
            $pluginPart[] = Inflector::camelize($parts[$i]);
            $possiblePlugin = implode('/', $pluginPart);
            if ($possiblePlugin && Plugin::loaded($possiblePlugin)) {
                $plugin = $possiblePlugin;
                $parts = array_slice($parts, $i + 1);
                break;
            }
        }

        $isAssetRequest = (isset($parts[0]) && $parts[0] === 'ASSETS');
        if ($isAssetRequest && Configure::read('debug')) {
            $parts = array_slice($parts, 1);
        } else {
            $isAssetRequest = false;
        }

        if ($plugin && Plugin::loaded($plugin)) {
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
        $fileFragment = implode(DS, $parts);
        $path = $isAssetRequest ? ROOT . DS . 'src' . DS . 'Assets' . DS : WWW_ROOT;

        return $path . $fileFragment;
    }
}
