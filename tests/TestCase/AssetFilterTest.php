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
use FrankFoerster\Asset\Routing\Filter\AssetFilter;

/**
 * Class AssetFilterTest
 * @property AssetFilter AssetHelper
 */
class AssetFilterTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Get a protected method via reflection to invoke it in tests.
     * E.g:
     *      $foo = self::getMethod('foo');
     *      $obj = new MyClass();
     *      $foo->invokeArgs($obj, array(...));
     *
     * @param string $class Namespaced class
     * @param string $method The name of the method.
     * @return ReflectionMethod
     */
    protected static function _getMethod($class, $method)
    {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method;
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
                'test.css',
                'webroot/test.css'
            ],
            [
                'test.js',
                'webroot/test.js'
            ],
            [
                'css/test.css',
                'webroot/css/test.css'
            ],
            [
                'js/test.js',
                'webroot/js/test.js'
            ],
            [
                'ASSETS/js/test.js',
                'src/Assets/js/test.js'
            ],
            [
                'test_plugin/css/plugin.css',
                'Plugin/TestPlugin/webroot/css/plugin.css'
            ],
            [
                'test_plugin/js/plugin.js',
                'Plugin/TestPlugin/webroot/js/plugin.js'
            ],
            [
                'test_plugin/ASSETS/js/test.js',
                'Plugin/TestPlugin/src/Assets/js/test.js'
            ],
            [
                'namespaced/plugin/css/plugin.css',
                'Plugin/Namespaced/Plugin/webroot/css/plugin.css'
            ],
            [
                'namespaced/plugin/js/plugin.js',
                'Plugin/Namespaced/Plugin/webroot/js/plugin.js'
            ],
            [
                'namespaced/plugin/ASSETS/js/test.js',
                'Plugin/Namespaced/Plugin/src/Assets/js/test.js'
            ],
            [
                'namespaced2/test_plugin/css/plugin.css',
                'Plugin/Namespaced2/TestPlugin/webroot/css/plugin.css'
            ],
            [
                'namespaced2/test_plugin/js/plugin.js',
                'Plugin/Namespaced2/TestPlugin/webroot/js/plugin.js'
            ],
            [
                'namespaced2/test_plugin/ASSETS/js/test.js',
                'Plugin/Namespaced2/TestPlugin/src/Assets/js/test.js'
            ],
            [
                'foo_bar/test_plugin/css/plugin.css',
                'Plugin/FooBar/TestPlugin/webroot/css/plugin.css'
            ],
            [
                'foo_bar/test_plugin/js/plugin.js',
                'Plugin/FooBar/TestPlugin/webroot/js/plugin.js'
            ],
            [
                'foo_bar/test_plugin/ASSETS/js/test.js',
                'Plugin/FooBar/TestPlugin/src/Assets/js/test.js'
            ],
            [
                'foo_bar/test_plugin/ASSETS/js/test.js',
                'Plugin/FooBar/TestPlugin/webroot/ASSETS/js/test.js',
                'debug' => false
            ]
        ];
    }

    /**
     * Test assets
     *
     * @dataProvider assetProvider
     * @param string $url
     * @param string $file
     * @param bool $debug
     * @return void
     */
    public function testAsset($url, $file, $debug = true)
    {
        Configure::write('debug', $debug);
        $method = self::_getMethod(AssetFilter::class, '_getAssetFile');
        $filter = new AssetFilter();
        $result = $method->invokeArgs($filter, [$url]);

        $expected = ROOT . DS . preg_replace('/\\//', DS, $file);
        $this->assertEquals($expected, $result);
    }
}
