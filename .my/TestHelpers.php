<?php
class TestHelpers {
    private static $testCount = 0;
    private static $failCount = 0;

    public static function assertEquals($expected, $actual, $message = '') {
        self::$testCount++;
        if ($expected !== $actual) {
            self::$failCount++;
            echo "Test Failed: {$message}\n";
            echo "Expected: " . print_r($expected, true) . "\n";
            echo "Got: " . print_r($actual, true) . "\n";
        } else {
            echo "Test Passed: {$message}\n";
        }
    }

    public static function assertTrue($condition, $message = '') {
        self::assertEquals(true, $condition, $message);
    }

    public static function assertFalse($condition, $message = '') {
        self::assertEquals(false, $condition, $message);
    }

    public static function summarizeTests() {
        echo "\nTotal Tests Run: " . self::$testCount . "\n";
        echo "Tests Failed: " . self::$failCount . "\n";
        echo "Tests Passed: " . (self::$testCount - self::$failCount) . "\n";
    }
}
