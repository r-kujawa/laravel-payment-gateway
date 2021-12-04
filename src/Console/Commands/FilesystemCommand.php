<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FilesystemCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new filesystem command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }
}
