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
        $updated = Suspensions::where('remaining_days', '>', 0)
            ->decrement('remaining_days');

        $this->info("Updated {$updated} suspension(s).");
    }
}
