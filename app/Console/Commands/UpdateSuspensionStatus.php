<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suspensions;

class UpdateSuspensionStatus extends Command
{
    protected $signature = 'suspensions:update-status';

    protected $description = 'Reduce suspension days daily';

    public function handle()
    {
        $updated = Suspensions::where('num_of_days_suspended', '>', 0)
            ->decrement('num_of_days_suspended');

        $this->info("Updated {$updated} suspension(s).");
    }
}