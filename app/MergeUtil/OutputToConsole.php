<?php

namespace App\MergeUtil;

trait OutputToConsole
{
    /**
     * print error to terminal
     * with red background and white foreground
     *
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->line($message, '1;37;41m');
    }

    /**
     * print line to terminal
     * with green foreground
     *
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        $this->line($message, '0;32;40m');
    }

    /**
     * print line to terminal
     * default to white foreground
     *
     * @param string $message
     * @param string $style
     * @return void
     */
    protected function line(
        string $message,
        string $style = '1;37;40m'
    ): void {
        printf("\e[{$style}%s\e[0m%s", $message, PHP_EOL);
    }
}