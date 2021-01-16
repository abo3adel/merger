<?php

namespace App\Commands;

use App\Helpers;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use SplFileInfo;

class ListCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dir:list {directory? : list files in this directory}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'list user created directories';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = Helpers::stubsPath();
        $wantedDir = $this->argument('directory');
        $files = [];

        chdir($path);

        if ($wantedDir) {
            $files =  array_map(
                fn (SplFileInfo $f) => $f->getBasename(),
                File::files($wantedDir)
            );
        } else {
            $files = glob('*', GLOB_ONLYDIR);
        }

        $this->warn("List of all " . ($wantedDir ? 'Files:' : 'Directories:'));

        foreach ($files as $file) {
            $this->info($file);
        }
    }
}
