<?php
/**
 * Copyright (c) Frank Förster (http://frankfoerster.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Frank Förster (http://frankfoerster.com)
 * @author Frank Förster <frank at frankfoerster.com>
 * @link https://github.com/frankfoerster/cakephp-environment CakePHP Environment Plugin
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
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

    public function testCssOnExistingFile()
    {
        $result = $this->AssetHelper->css('css/test.css');
        $this->assertTextContains('<link rel="stylesheet" type="text/css" href="css/test.css', $result);
        $this->assertTextContains('?t=', $result);
    }

    public function testCssOnNonExistingFile()
    {
        $result = $this->AssetHelper->css('css/foobar.css');
        $this->assertTextContains('<link rel="stylesheet" type="text/css" href="css/foobar.css', $result);
        $this->assertTextNotContains('?t=', $result);
    }

    public function testCssForPluginOnExistingFile()
    {
        $result = $this->AssetHelper->css('css/plugin.css', 'TestPlugin');
        $this->assertTextContains('<link rel="stylesheet" type="text/css" href="test_plugin/css/plugin.css', $result);
        $this->assertTextContains('?t=', $result);
    }

    public function testCssForPluginOnNonExistingFile()
    {
        $result = $this->AssetHelper->css('css/test.css', 'TestPlugin');
        $this->assertTextContains('<link rel="stylesheet" type="text/css" href="test_plugin/css/test.css', $result);
        $this->assertTextNotContains('?t=', $result);
    }

    /**
     * @expectedException \Cake\Core\Exception\MissingPluginException
     */
    public function testCssForMissingPlugin()
    {
        $this->AssetHelper->css('css/test.css', 'Whatever');
    }

    public function testJsOnExistingFile()
    {
        $result = $this->AssetHelper->js('js/test.js');
        $this->assertTextContains('<script type="text/javascript" src="js/test.js', $result);
        $this->assertTextContains('?t=', $result);
    }

    public function testJsOnNonExistingFile()
    {
        $result = $this->AssetHelper->js('js/foobar.js');
        $this->assertTextContains('<script type="text/javascript" src="js/foobar.js', $result);
        $this->assertTextNotContains('?t=', $result);
    }

    public function testJsForPluginOnExistingFile()
    {
        $result = $this->AssetHelper->js('js/plugin.js', 'TestPlugin');
        $this->assertTextContains('<script type="text/javascript" src="test_plugin/js/plugin.js', $result);
        $this->assertTextContains('?t=', $result);
    }

    public function testJsForPluginOnNonExistingFile()
    {
        $result = $this->AssetHelper->js('js/test.js', 'TestPlugin');
        $this->assertTextContains('<script type="text/javascript" src="test_plugin/js/test.js', $result);
        $this->assertTextNotContains('?t=', $result);
    }

    /**
     * @expectedException \Cake\Core\Exception\MissingPluginException
     */
    public function testJsForMissingPlugin()
    {
        $this->AssetHelper->js('js/test.js', 'Whatever');
    }
}
