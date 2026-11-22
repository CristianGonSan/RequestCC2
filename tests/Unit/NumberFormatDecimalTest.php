<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NumberFormatDecimalTest extends TestCase
{
    public function test_it_formats_decimal_strings_without_losing_precision(): void
    {
        $this->assertSame('1,000,000,000,000.00', number_format_decimal('999999999999.995', 2));
        $this->assertSame('-1.01', number_format_decimal('-1.005', 2));
        $this->assertSame('1.234,50', number_format_decimal('1234.5', 2, ',', '.'));
        $this->assertSame('0.00', number_format_decimal('-0.004', 2));
    }

    public function test_it_rejects_negative_precision(): void
    {
        $this->expectException(InvalidArgumentException::class);

        number_format_decimal('1', -1);
    }

    public function test_it_rejects_non_decimal_strings(): void
    {
        $this->expectException(InvalidArgumentException::class);

        number_format_decimal('1e3', 2);
    }
}
