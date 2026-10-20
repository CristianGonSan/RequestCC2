<?php

namespace App\Contracts\Common;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasMessages
{
    public function messages(): MorphMany;
}
