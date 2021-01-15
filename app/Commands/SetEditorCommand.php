<?php

namespace App\Commands;

use Dotenv\Dotenv;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class SetEditorCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'editor:set {editor-name : editor terminal name}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'set default editor to open stub files with';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $editor = $this->argument('editor-name');
        $filePath = base_path() . DIRECTORY_SEPARATOR;

        $file = [];

        if (file_exists($filePath . '.env')) {
            $file = Dotenv::createArrayBacked(base_path())->load();

        }

        $file['EDITOR'] = $editor;

        $output = [];
        foreach ($file as $key => $val) {
            $output[] = strpos($val, ' ') === false ?
                $key . '=' . $val :
                $key . '="' . $val .'"';
        }

        File::put($filePath . '.env', implode("\n", $output));
    }
}
