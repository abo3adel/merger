<?php

namespace App\Commands;

use App\Helpers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class OpenCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'open {file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'open stub file for editing';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = Helpers::stubsPath($this->argument('file'));
        $fileName = File::basename($file);        

        if (!file_exists($file)) {
            $this->error('file ' . $fileName . ' not found');
            return;
        }

        $this->warn('Opening file...');
        exec(config('app.editor') . ' ' . $file);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
