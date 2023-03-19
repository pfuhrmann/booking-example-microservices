<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessReservationUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Reservation $reservation)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // N/A
        // External job processor (email service)
    }

    public function __serialize()
    {
        return $this->reservation->attributesToArray();
    }
}
