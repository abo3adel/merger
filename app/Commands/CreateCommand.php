<?php

namespace App\Commands;

use App\Helpers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class CreateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create 
        {file : the file path to be created}
        {--d|do-not-open : do not open the file after creating}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'create new stub for merge';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = $this->argument('file');

        // check if file was created
        if (file_exists(Helpers::stubsPath($file))) {
            $this->error('file was created already');
            return;
        }

        $this->warn("Creating: " . $file);

        $created = Storage::put(
            Helpers::STUBS . DIRECTORY_SEPARATOR . $file,
            ''
        );

        if (!$created) {
            $this->error('an error occured, please try again later');
        }

        $this->info("File " . File::basename($file) . ' was Created Successfully');

        if ($this->option('do-not-open')) {
            return;
        }

        $this->warn('Opening file...');
        exec(config('editor') . ' ' . Helpers::stubsPath($file));
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
