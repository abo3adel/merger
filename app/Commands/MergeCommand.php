<?php

namespace App\Commands;

use App\Helpers;
use App\MergeUtil\EnvFile;
use App\MergeUtil\JsonFile;
use App\MergeUtil\MergerInterface;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class MergeCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'merge 
        {dir : the directory that holds down stub files}
        {--f|force : replace with user defined values in stub files}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'merge stub files with original files';

    public $basePath = '';
    public $userPath = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->basePath = Helpers::stubsPath($this->argument('dir'));
        $this->userPath = getcwd();

        if (!is_dir($this->basePath)) {
            $this->error('Directory not found');
            return;
        }

        $this->info('Fetching your files...');
        $this->scanDir($this->basePath);
    }

    /**
     * keep scanning directory for files to update
     *
     * @param string $dir
     * @return void
     */
    private function scanDir(string $dir): void
    {
        chdir($dir);
        $files = glob('.env') + glob('*');
        $scanned = [];
        foreach ($files as $file) {
            if (is_dir($file) && !isset($scanned[$file])) {
                $scanned[] = $file;
                if (!$this->pathHaveStubs($file)) {
                    continue;
                }
                $this->scanDir($file);
                continue;
            }
            $this->warn('Merging ' . $file . ' ...');
            $this->attachToMerger($file, $dir);
            $this->info($file . ' Merged Successfully');
        }

        // if directory was changed then go back
        !Str::contains(getcwd(), $dir) ?: chdir('..');
    }

    /**
     * attach to the proper merger instance based on file extension
     *
     * @param string $file
     * @param string $dir
     * @return void
     */
    private function attachToMerger(string $file, string $dir): void
    {
        $type = File::extension($file);

        if ($type === 'json') {
            $this->mergerInstance(new JsonFile, $file, $dir);
        } elseif ($type === 'env') {
            $this->mergerInstance(new EnvFile, $file, $dir);
        }
    }

    /**
     * iniate proper merger class and merge files
     *
     * @param MergerInterface $merger
     * @param string $file
     * @param string $dir
     * @return void
     */
    private function mergerInstance(
        MergerInterface $merger,
        string $file,
        string $dir
    ): void {
        (new $merger)
          ->setUserDir($this->userPath)
            ->setForce($this->option('force'))
            ->merge($file, $dir);
    }

    /**
     * check if current directory have any stubs to upload
     *
     * @param string $dir
     * @return boolean
     */
    private function pathHaveStubs(string $dir): bool
    {
        chdir($this->basePath);
        $result = is_dir($dir);
        chdir($this->userPath);

        return $result;
    }
}
