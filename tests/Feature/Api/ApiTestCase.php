<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }
}
