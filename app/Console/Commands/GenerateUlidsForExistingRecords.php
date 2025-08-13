<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateUlidsForExistingRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ulid:generate {--force : Force regeneration even if ULID exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate ULIDs for existing records without ULIDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting ULID generation for existing records...');
        
        // Generate ULIDs for Events
        $this->generateForEvents();
        
        // Generate ULIDs for Bookings
        $this->generateForBookings();
        
        // Generate ULIDs for Tickets
        $this->generateForTickets();
        
        $this->info('ULID generation completed successfully!');
    }
    
    private function generateForEvents()
    {
        $this->info('Generating ULIDs for Events...');
        
        $query = DB::table('events');
        if (!$this->option('force')) {
            $query->where(function($q) {
                $q->whereNull('ulid')->orWhere('ulid', '');
            });
        }
        
        $events = $query->get();
        $count = 0;
        
        foreach ($events as $event) {
            DB::table('events')
                ->where('id', $event->id)
                ->update(['ulid' => (string) Str::ulid()]);
            $count++;
        }
        
        $this->info("Generated ULIDs for {$count} events.");
    }
    
    private function generateForBookings()
    {
        $this->info('Generating ULIDs for Bookings...');
        
        $query = DB::table('bookings');
        if (!$this->option('force')) {
            $query->where(function($q) {
                $q->whereNull('ulid')->orWhere('ulid', '');
            });
        }
        
        $bookings = $query->get();
        $count = 0;
        
        foreach ($bookings as $booking) {
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update(['ulid' => (string) Str::ulid()]);
            $count++;
        }
        
        $this->info("Generated ULIDs for {$count} bookings.");
    }
    
    private function generateForTickets()
    {
        $this->info('Generating ULIDs for Tickets...');
        
        $query = DB::table('tickets');
        if (!$this->option('force')) {
            $query->where(function($q) {
                $q->whereNull('ulid')->orWhere('ulid', '');
            });
        }
        
        $tickets = $query->get();
        $count = 0;
        
        foreach ($tickets as $ticket) {
            DB::table('tickets')
                ->where('id', $ticket->id)
                ->update(['ulid' => (string) Str::ulid()]);
            $count++;
        }
        
        $this->info("Generated ULIDs for {$count} tickets.");
    }
}
