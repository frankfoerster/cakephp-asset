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
namespace FrankFoerster\Asset\View\Helper;

use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\View\Helper;

/**
 * Class AssetHelper
 *
 * @property Helper\UrlHelper Url
 */
class AssetHelper extends Helper
{
    /**
     * {@inheritDoc}
     */
    public $helpers = [
        'Url'
    ];

    /**
     * Output a link stylesheet tag for a specific css file and optionally
     * append a last modified timestamp to clear the browser cache.
     *
     * @param string $path The path to the css file relative to WEBROOT
     * @param bool $plugin Either false or the name of a plugin.
     * @param bool $appendTime Whether to append a last modified timestamp to the url.
     * @return string
     */
    public function css($path, $plugin = false, $appendTime = true)
    {
        $href = $this->_getUrl($path, $plugin, $appendTime);
        return '<link rel="stylesheet" type="text/css" href="' . $href . '">';
    }

    /**
     * Output a script tag for a specific js file and optionally
     * append a last modified timestamp to clear the browser cache.
     *
     * @param string $path The path to the css file relative to the app or plugin webroot.
     * @param bool|string $plugin Either false or the name of a plugin.
     * @param bool $appendTime Whether to append a last modified timestamp to the url.
     * @return string
     */
    public function js($path, $plugin = false, $appendTime = true)
    {
        $src = $this->_getUrl($path, $plugin, $appendTime);
        return '<script type="text/javascript" src="' . $src . '"></script>';
    }

    /**
     * Get the asset url for a specific file.
     *
     * @param string $path The path to the css file relative to the app or plugin webroot.
     * @param bool|string $plugin Either false or the name of a plugin.
     * @param bool $appendTime Whether to append a last modified timestamp to the url.
     * @return string
     */
    protected function _getUrl($path, $plugin, $appendTime = true)
    {
        $absPath = $this->_getBasePath($plugin) . $path;
        $time = $appendTime ? $this->_getModifiedTime($absPath) : '';
        $path = ($plugin !== false) ? $plugin . '.' . $path : $path;

        return $this->Url->assetUrl($path) . $time;
    }

    /**
     * Get the base path to the app webroot or a plugin webroot.
     *
     * @param bool|string $plugin Either false or the name of a plugin.
     * @return string
     */
    protected function _getBasePath($plugin = false)
    {
        if ($plugin !== false) {
            if (!Plugin::loaded($plugin)) {
                throw new MissingPluginException('Plugin ' . $plugin . ' is not loaded.');
            }
            $pluginPath = Plugin::path($plugin);
            return $pluginPath . DS . 'webroot' . DS;
        }
        return WWW_ROOT;
    }

    /**
     * Get the ?t=1231412515 timestamp part built from the last modified time of the file.
     *
     * @param string $absPath The absolute path to the file.
     * @return string
     */
    protected function _getModifiedTime($absPath)
    {
        if (file_exists($absPath)) {
            return '?t=' . filemtime($absPath);
        }
        return '';
    }
}
