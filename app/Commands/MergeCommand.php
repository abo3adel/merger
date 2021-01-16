<?php

namespace App\Commands;

use App\Helpers;
use App\MergeUtil\FileType\AppendToFile;
use App\MergeUtil\FileType\Env;
use App\MergeUtil\FileType\Git;
use App\MergeUtil\FileType\Install;
use App\MergeUtil\FileType\Json;
use App\MergeUtil\FileType\Xml;
use App\MergeUtil\FileType\Yaml;
use App\MergeUtil\MergerInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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
        {--f|force : replace with user defined values in stub files}
        {--d|no-append : do not append stubs that has no user files with same names}
        {--n|no-install : do not run install file}
        ';

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
        $files = glob('.[eng]*') + glob('*');
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
        $noInstall = $this->option('no-install');

        if (!$noInstall && ($file === 'install.yml' || $file === 'install.yaml')) {
            $this->mergerInstance(new Install, $file, $dir);
        } elseif ($type === 'json') {
            $this->mergerInstance(new Json, $file, $dir);
        } elseif ($type === 'env') {
            $this->mergerInstance(new Env, $file, $dir);
        } elseif ($type === 'yml' || $type === 'yaml') {
            $this->mergerInstance(new Yaml, $file, $dir);
        } elseif (strpos($type, 'git') !== false) {
            $this->mergerInstance(new Git, $file, $dir);
        } elseif ($type === 'xml' || $type === 'dist') {
            $this->mergerInstance(new Xml, $file, $dir);
        } else {
            // append text to any file type
            $this->mergerInstance(new AppendToFile, $file, $dir);
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
            ->setNoAppend($this->option('no-append'))
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
