<?php

namespace PHPExperts\ConciseUuid;

use Illuminate\Support\ServiceProvider;

class ConciseUuidServiceProvider extends ServiceProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['conciseuuid'];
    }
}
