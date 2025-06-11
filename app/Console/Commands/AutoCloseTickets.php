<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

date_default_timezone_set('Asia/Manila');

class AutoCloseTickets extends Command
{
    protected $signature = 'tickets:auto-close';
    protected $description = 'Automatically close tickets and set for_acceptance to null after 3 days, excluding declined tickets.';

    public function handle()
    {
        Log::info('AutoCloseTickets command started.');

        // Close tickets, excluding declined
        $closedTickets = Ticket::where('status', 'awaiting_acceptance')
            ->where('auto_close_date', '<=', now())
            ->where('for_acceptance', '!=', 'Declined') // Add this line
            ->update(['for_acceptance' => 'null']);

        Log::info('Closed ' . $closedTickets . ' tickets.');

        // Set for_acceptance to null, excluding declined
        $updatedForAcceptance = Ticket::where('auto_close_date', '<=', now())
            ->where('for_acceptance', '!=', 'Declined') // Add this line
            ->update(['for_acceptance' => null]);

        Log::info('Updated for_acceptance for ' . $updatedForAcceptance . ' tickets.');

        Log::info('AutoCloseTickets command finished.');
    }
}