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

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use FrankFoerster\Asset\View\Helper\AssetHelper;

/**
 * Class AssetHelperTest
 * @property AssetHelper AssetHelper
 */
class AssetHelperTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $request = new \Cake\Network\Request();
        $response = new \Cake\Network\Response();
        $view = new \Cake\View\View($request, $response);
        $this->AssetHelper = new AssetHelper($view);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testLoadedHelpers()
    {
        $this->assertTrue(in_array('Url', $this->AssetHelper->helpers));
    }

    /**
     * Data provider for asset filter
     *
     * - theme assets.
     * - plugin assets.
     * - plugin assets in sub directories.
     * - unknown plugin assets.
     *
     * @return array
     */
    public static function assetProvider()
    {
        return [
            [
                'css',
                'css/test.css',
                false,
                'css/test.css',
                true
            ],
            [
                'css',
                'css/foobar.css',
                false,
                'css/foobar.css',
                false
            ],
            [
                'css',
                'css/plugin.css',
                'TestPlugin',
                'test_plugin/css/plugin.css',
                true
            ],
            [
                'css',
                'css/test.css',
                'TestPlugin',
                'test_plugin/css/test.css',
                false
            ],
            [
                'js',
                'js/test.js',
                false,
                'js/test.js',
                true
            ],
            [
                'js',
                'js/foobar.js',
                false,
                'js/foobar.js',
                false
            ],
            [
                'js',
                'ASSETS/js/test.js',
                false,
                'ASSETS/js/test.js',
                true
            ],
            [
                'js',
                'ASSETS/js/foobar.js',
                false,
                'ASSETS/js/foobar.js',
                false
            ],
            [
                'js',
                'js/plugin.js',
                'TestPlugin',
                'test_plugin/js/plugin.js',
                true
            ],
            [
                'js',
                'js/test.js',
                'TestPlugin',
                'test_plugin/js/test.js',
                false
            ],
            [
                'js',
                'ASSETS/js/test.js',
                'TestPlugin',
                'test_plugin/ASSETS/js/test.js',
                true
            ],
            [
                'js',
                'ASSETS/js/foobar.js',
                'TestPlugin',
                'test_plugin/ASSETS/js/foobar.js',
                false
            ],
            [
                'js',
                'js/plugin.js',
                'Namespaced/Plugin',
                'namespaced/plugin/js/plugin.js',
                true
            ],
            [
                'css',
                'css/plugin.css',
                'Namespaced/Plugin',
                'namespaced/plugin/css/plugin.css',
                true
            ],
            [
                'js',
                'ASSETS/js/test.js',
                'Namespaced/Plugin',
                'namespaced/plugin/ASSETS/js/test.js',
                true
            ],
            [
                'js',
                'js/plugin.js',
                'Namespaced2/TestPlugin',
                'namespaced2/test_plugin/js/plugin.js',
                true
            ],
            [
                'css',
                'css/plugin.css',
                'Namespaced2/TestPlugin',
                'namespaced2/test_plugin/css/plugin.css',
                true
            ],
            [
                'js',
                'ASSETS/js/test.js',
                'Namespaced2/TestPlugin',
                'namespaced2/test_plugin/ASSETS/js/test.js',
                true
            ],
            [
                'js',
                'js/plugin.js',
                'FooBar/TestPlugin',
                'foo_bar/test_plugin/js/plugin.js',
                true
            ],
            [
                'css',
                'css/plugin.css',
                'FooBar/TestPlugin',
                'foo_bar/test_plugin/css/plugin.css',
                true
            ],
            [
                'js',
                'ASSETS/js/test.js',
                'FooBar/TestPlugin',
                'foo_bar/test_plugin/ASSETS/js/test.js',
                true
            ],
            [
                'js',
                'js/foobar.js',
                'FooBar/TestPlugin',
                'foo_bar/test_plugin/js/foobar.js',
                false
            ],
            [
                'js',
                'ASSETS/js/foobar.js',
                'FooBar/TestPlugin',
                'foo_bar/test_plugin/ASSETS/js/foobar.js',
                false
            ]
        ];
    }

    /**
     * Test the asset helper methods.
     *
     * @dataProvider assetProvider
     * @param string $method
     * @param string $asset
     * @param bool|string $plugin
     * @param string $expected
     * @param bool $timestamp
     * @return void
     */
    public function testAssetHelper($method, $asset, $plugin, $expected, $timestamp)
    {
        if (!method_exists($this->AssetHelper, $method)) {
            user_error('Method "' . $method . '"" does not exist on AssetHelper and therefore cannot be tested.');
        }

        $result = $this->AssetHelper->$method($asset, $plugin);
        switch ($method) {
            case 'css':
                $this->assertTextContains('<link rel="stylesheet" type="text/css" href="' . $expected, $result);
                break;
            case 'js':
                $this->assertTextContains('<script type="text/javascript" src="' . $expected, $result);
                break;
        }
        if ($timestamp) {
            $this->assertTextContains('?t=', $result);
        } else {
            $this->assertTextNotContains('?t=', $result);
        }
    }

    /**
     * @expectedException \Cake\Core\Exception\MissingPluginException
     */
    public function testCssForMissingPlugin()
    {
        $this->AssetHelper->css('css/test.css', 'Whatever');
    }

    /**
     * @expectedException \Cake\Core\Exception\MissingPluginException
     */
    public function testJsForMissingPlugin()
    {
        $this->AssetHelper->js('js/test.js', 'Whatever');
    }
}
