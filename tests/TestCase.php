<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * This method boots the Laravel application for tests. It's a small
     * replacement for the default CreatesApplication trait to ensure the
     * test suite can bootstrap in this repository setup.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

    $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
