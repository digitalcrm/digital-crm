<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearRayScreen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-screen';

    public function handle()
    {
        ray()->clearScreen();
        // ray('hello world');
    }
}
