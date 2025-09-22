<?php

declare(strict_types=1);

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;

class PocCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate results on the fly for testing purposes only.';

    /**
     * Execute the console command.
     */
    public function handle(): void {}
}
