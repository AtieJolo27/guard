<?php

namespace App\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class GuardViolationCatalog
{
    // Routine security checks and immediately visible conduct only.
    // Keep academic, membership, conviction, and accumulated-offense decisions out.
    public static function query(): Builder
    {
        return DB::table('violations')->whereIn(DB::raw('LOWER(violation_name)'), [
            'unbecoming behavior', 'excessive noise',
            'provocation to fight', 'littering / spitting',
            'smoking on campus',
            'possession / use of alcohol',
            'possession of bladed weapons',

            'unlawful possession of firearms',

            'improper attire', 'improper uniform', 'improper uniform attire', 'uniform',
            'illegal possession of dangerous equipment',
            'illegal possession of dangerous equipments',
        ]);
    }
}
