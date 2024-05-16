<?php

class UnitTest {
    private static $testCount = 0;
    private static $failCount = 0;

    public static function assertEquals($expected, $actual, $message = '') {
        self::$testCount++;
        if ($expected !== $actual) {
            self::$failCount++;
            echo "FAIL: {$message}\n";
            echo "Expected: " . print_r($expected, true) . "\n";
            echo "Actual: " . print_r($actual, true) . "\n";
        } else {
            echo "PASS: {$message}\n";
        }
    }

    public static function assertTrue($condition, $message = '') {
        self::assertEquals(true, $condition, $message);
    }

    public static function assertFalse($condition, $message = '') {
        self::assertEquals(false, $condition, $message);
    }

    public static function summarizeTests() {
        echo "\n" . "Total Tests: " . self::$testCount . ", Failed: " . self::$failCount . "\n";
        if (self::$failCount === 0) {
            echo "All tests passed successfully!\n";
        } else {
            echo "Some tests failed. Check the logs above.\n";
        }
    }
}
