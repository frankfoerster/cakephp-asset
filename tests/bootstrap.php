<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
// include autoload from Composer
use Cake\Core\Plugin;

require dirname(__DIR__) . '/vendor/autoload.php';
// include paths from CakePHP
require dirname(__DIR__) . '/tests/paths.php';
// disable cache to avoid errors on tests
\Cake\Cache\Cache::disable();

Plugin::load('TestPlugin', [
    'path' => ROOT . DS . 'Plugin' . DS . 'TestPlugin' . DS
]);

Plugin::load('Namespaced/Plugin', [
    'path' => ROOT . DS . 'Plugin' . DS . 'Namespaced' . DS . 'Plugin' . DS
]);

Plugin::load('Namespaced2/TestPlugin', [
    'path' => ROOT . DS . 'Plugin' . DS . 'Namespaced2' . DS . 'TestPlugin' . DS
]);

Plugin::load('FooBar/TestPlugin', [
    'path' => ROOT . DS . 'Plugin' . DS . 'FooBar' . DS . 'TestPlugin' . DS
]);
