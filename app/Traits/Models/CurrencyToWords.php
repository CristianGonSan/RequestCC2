<?php

namespace App\Traits\Models;

use NumberToWords\NumberToWords;

trait CurrencyToWords
{
    public function toCurrencyWords(string $attribute): string
    {
        $value = $this->getAttribute($attribute);

        if (blank($value) || !is_numeric($value)) {
            return 'NaN';
        }

        $numeric    = (float) $value;
        $isNegative = $numeric < 0;

        $formatted           = number_format(abs($numeric), 2, '.', '');
        [$integer, $decimal] = explode('.', $formatted);

        $transformer = (new NumberToWords)->getNumberTransformer('es');

        $intText = $transformer->toWords((int) $integer);
        $decText = $transformer->toWords((int) $decimal);

        $sign = $isNegative ? 'menos ' : '';

        return "{$sign}{$intText} pesos con {$decText} centavos";
    }
}
