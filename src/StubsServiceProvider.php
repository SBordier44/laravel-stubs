<?php

declare(strict_types=1);

namespace SBordier44\Larastubs;

use Illuminate\Support\ServiceProvider;
use SBordier44\Larastubs\Console\Commands\PublishCommand;

class StubsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(commands: [PublishCommand::class]);
        }
    }
}
