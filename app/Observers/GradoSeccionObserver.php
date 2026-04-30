<?php

namespace App\Observers;

use App\Models\GradoSeccion;
use App\Services\MateriaCNB;

class GradoSeccionObserver
{
    public function created(GradoSeccion $gradoSeccion): void
    {
        MateriaCNB::seedParaGradoSeccion($gradoSeccion);
    }
}
