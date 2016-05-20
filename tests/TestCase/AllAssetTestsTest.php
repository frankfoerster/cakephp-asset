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

use Cake\TestSuite\TestSuite;

class AllAssetTestsTest extends TestSuite
{

    /**
     * Defines all tests for this suite.
     *
     * @return TestSuite
     */
    public static function suite()
    {
        $suite = new TestSuite('All Asset Tests');

        $path = dirname(__FILE__) . DS;
        $suite->addTestDirectoryRecursive($path);

        return $suite;
    }
}
