<?php

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
