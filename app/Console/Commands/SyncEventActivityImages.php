<?php
// app/Console/Commands/SyncEventActivityImages.php

namespace App\Console\Commands;

use App\Models\EventActivity;
use Illuminate\Console\Command;

class SyncEventActivityImages extends Command
{
    // This is the name you'll type in terminal
    protected $signature = 'events:sync-images';
    
    // Description shown when listing commands
    protected $description = 'Sync event/activity images from public/images folder';

    public function handle()
    {
        $this->info('Starting image sync for events and activities...');
        
        // Call the static method we created in the model
        $updated = EventActivity::syncAllImagesFromFolder();
        
        $this->info("Sync completed! {$updated} items have images in the folder.");
        
        return Command::SUCCESS;
    }
}