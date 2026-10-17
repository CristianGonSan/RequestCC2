<?php

namespace App\Contracts\Common;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasMessages
{
    public function messages(): MorphMany;

    /** URL del detalle donde se muestran los mensajes. */
    public function messageUrl(): string;

    /** Nombre legible del recurso: "Ingreso", "Solicitud de material", etc. */
    public function messageLabel(): string;
}
