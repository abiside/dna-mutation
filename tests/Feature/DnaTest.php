<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class DnaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Base url to build the endpoint
     *
     * @var string
     */
    protected $baseUrl = '/api/v1/mutation';

    /** @test */
    public function it_finds_mutation_for_the_given_code()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $payload = [
            'dna' => [
                "ATGCGA",
                "CAGTGC",
                "TTATGT",
                "AGAAGG",
                "CCCCTA",
                "TCACTG",
            ],
        ];

        $this->assertDatabaseMissing('dna_codes', [
            'code' => implode(',', Arr::get($payload, 'dna')),
        ]);

        $this->postJson($url, $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('dna_codes', [
            'code' => implode(',', Arr::get($payload, 'dna')),
        ]);
    }

    /** @test */
    public function it_finds_mutation_for_an_already_saved_code()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $payload = [
            'dna' => [
                "ATGCGA",
                "CAGTGC",
                "TTATGT",
                "AGAAGG",
                "CCCCTA",
                "TCACTG",
            ],
        ];

        $this->assertDatabaseMissing('dna_codes', [
            'code' => implode(',', Arr::get($payload, 'dna')),
        ]);

        $this->postJson($url, $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('dna_codes', [
            'code' => implode(',', Arr::get($payload, 'dna')),
        ]);

        $this->postJson($url, $payload)
            ->assertStatus(200);

        $this->assertDatabaseCount('dna_codes', 1);
    }

    /** @test */
    public function it_returns_an_exception_for_the_missing_dna_attribute()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $this->postJson($url)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'dna' => [
                        'The dna field is required.',
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_returns_an_exception_for_a_non_authenticated_user()
    {
        $user = User::factory()->create();

        $url = $this->getEndpoint();

        $this->postJson($url)
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /** @test */
    public function it_returns_an_exception_for_malformed_code_string()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $payload = [
            'dna' => [
                "ATGCGS",
                "CAGTGC",
                "TTATGT",
                "AGAAGG",
                "CCCCTA",
                "TCACTG",
            ],
        ];

        $this->postJson($url, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'dna.0' => [
                        'The dna.0 format is invalid.',
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_returns_an_exception_for_a_missing_code_string()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $payload = [
            'dna' => [
                "ATGCGA",
                "CAGTGC",
                "TTATGT",
                "AGAAGG",
                "CCCCTA",
            ],
        ];

        $this->postJson($url, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'dna' => [
                        'The dna must have at least 6 items.',
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_returns_an_exception_for_a_missing_dna_code()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $payload = [];

        $this->postJson($url, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'dna' => [
                        'The dna field is required.',
                    ],
                ],
            ]);
    }
}
