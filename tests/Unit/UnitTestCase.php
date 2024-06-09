<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Tests\TestCase;

abstract class UnitTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if (!$this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            call_user_func($callback);
        }

        Facade::clearResolvedInstances();

        Model::setEventDispatcher($this->app['events']);

        $this->setUpHasRun = true;
    }
}
