<?php

namespace BDSearch\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
        Monkey\Functions\stubs([
            'apply_filters'    => fn($hook, $value, ...$args) => $value,
            'wp_strip_all_tags' => fn($string) => strip_tags((string) $string),
            'strip_shortcodes'  => fn($content) => $content,
        ]);
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
