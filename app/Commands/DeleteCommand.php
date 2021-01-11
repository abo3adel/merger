<?php

namespace App\Commands;

use App\Helpers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class DeleteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'delete {file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'delete stub file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = Helpers::stubsPath($this->argument('file'));
        $fileName = File::basename($file);

        if (!File::exists($file)) {
            $this->error('file ' . $fileName . ' not found');
            return;
        }

        $deleted = unlink($file);

        if (!$deleted) {
            $this->error('an error occured');
            return;
        }

        $this->info($fileName . ' Delete Successfully');
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
