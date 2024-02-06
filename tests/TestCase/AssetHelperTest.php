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

use Cake\Core\Exception\MissingPluginException;
use Cake\Http\Response;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use FrankFoerster\Asset\View\Helper\AssetHelper;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Class AssetHelperTest
 * @property AssetHelper AssetHelper
 */
class AssetHelperTest extends TestCase
{
    protected AssetHelper $AssetHelper;

    public function setUp(): void
    {
        parent::setUp();

        \Cake\Routing\Router::reload();
        $this->loadRoutes();

        $this->loadPlugins([
            \FooBar\TestPlugin\Plugin::class,
            \Namespaced\Plugin\Plugin::class,
            \Namespaced2\TestPlugin\Plugin::class,
            \TestPlugin\Plugin::class
        ]);

        $response = new Response(['charset' => 'utf8']);
        $view = new View(null, $response);
        $this->AssetHelper = new AssetHelper($view);
        $view->loadHelper('Url');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testLoadedHelpers(): void
    {
        $this->assertTrue(in_array('Url', array_keys($this->AssetHelper->helpers)));
    }

    /**
     * Data provider for asset filter
     *
     * - theme assets.
     * - plugin assets.
     * - plugin assets in subdirectories.
     * - unknown plugin assets.
     *
     * @return array
     */
    public static function assetProvider(): array
    {
        return [
            [
                'css',
                'css/test.css',
                false,
                '/css/test.css',
                true
            ],
            [
                'css',
                'css/foobar.css',
                false,
                '/css/foobar.css',
                false
            ],
            [
                'css',
                'css/plugin.css',
                'TestPlugin',
                '/test_plugin/css/plugin.css',
                true
            ],
            [
                'css',
                'css/test.css',
                'TestPlugin',
                '/test_plugin/css/test.css',
                false
            ],
            [
                'js',
                'js/test.js',
                false,
                '/js/test.js',
                true
            ],
            [
                'js',
                'js/foobar.js',
                false,
                '/js/foobar.js',
                false
            ],
            [
                'js',
                'js/plugin.js',
                'TestPlugin',
                '/test_plugin/js/plugin.js',
                true
            ],
            [
                'js',
                'js/test.js',
                'TestPlugin',
                '/test_plugin/js/test.js',
                false
            ],
            [
                'js',
                'js/plugin.js',
                'Namespaced/Plugin',
                '/namespaced/plugin/js/plugin.js',
                true
            ],
            [
                'css',
                'css/plugin.css',
                'Namespaced/Plugin',
                '/namespaced/plugin/css/plugin.css',
                true
            ],
            [
                'js',
                'js/plugin.js',
                'Namespaced2/TestPlugin',
                '/namespaced2/test_plugin/js/plugin.js',
                true
            ],
            [
                'css',
                'css/plugin.css',
                'Namespaced2/TestPlugin',
                '/namespaced2/test_plugin/css/plugin.css',
                true
            ],
            [
                'js',
                'js/plugin.js',
                'FooBar/TestPlugin',
                '/foo_bar/test_plugin/js/plugin.js',
                true
            ],
            [
                'css',
                'css/plugin.css',
                'FooBar/TestPlugin',
                '/foo_bar/test_plugin/css/plugin.css',
                true
            ],
            [
                'js',
                'js/foobar.js',
                'FooBar/TestPlugin',
                '/foo_bar/test_plugin/js/foobar.js',
                false
            ]
        ];
    }

    /**
     * Test the asset helper methods.
     *
     * @param string $method
     * @param string $asset
     * @param bool|string $plugin
     * @param string $expected
     * @param bool $timestamp
     * @return void
     */
    #[DataProvider('assetProvider')]
    public function testAssetHelper(string $method, string $asset, bool|string $plugin, string $expected, bool $timestamp): void
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

    public function testCssForMissingPlugin(): void
    {
        $this->expectException(MissingPluginException::class);
        $this->AssetHelper->css('css/test.css', 'Whatever');
    }

    public function testJsForMissingPlugin(): void
    {
        $this->expectException(MissingPluginException::class);
        $this->AssetHelper->js('js/test.js', 'Whatever');
    }
}
