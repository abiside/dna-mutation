<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Returns the url for the endpoint based on the resource url
     *
     * @param  string  $url
     * @return string
     */
    protected function getEndpoint(string $url = null): string
    {
        $url = trim($url, '/');
        $baseUrl = trim($this->baseUrl, '/');

        return "{$baseUrl}/{$url}";
    }

    /**
     * Returns the auth user to make the requests
     *
     * @param  \App\Models\User|null  $user
     * @return \App\Models\User
     */
    public function getAuthUser($user = null)
    {
        return Sanctum::actingAs($user ?? User::factory()->create());
    }
}
