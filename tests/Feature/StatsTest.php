<?php

namespace Tests\Feature;

use App\Models\Dna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Base url to build the endpoint
     *
     * @var string
     */
    protected $baseUrl = '/api/v1/stats';

    /** @test */
    public function it_returns_zeros_for_empty_database()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $this->assertDatabaseCount('dna_codes', 0);

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson([
                'count_mutations' => 0,
                'count_no_mutations' => 0,
                'ratio' => 0,
            ]);
    }

    /** @test */
    public function it_returns_only_results_with_mutations()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $codes = Dna::factory()->count(5)->withMutation()->create();

        $this->assertDatabaseCount('dna_codes', 5);

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson([
                'count_mutations' => 5,
                'count_no_mutations' => 0,
                'ratio' => 1,
            ]);
    }

    /** @test */
    public function it_returns_half_results_with_mutations()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $withMutations = Dna::factory()->count(2)->withMutation()->create();
        $withoutMutations = Dna::factory()->count(2)->withoutMutation()->create();

        $this->assertDatabaseCount('dna_codes', 4);

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson([
                'count_mutations' => 2,
                'count_no_mutations' => 2,
                'ratio' => .5,
            ]);
    }

    /** @test */
    public function it_returns_one_third_results_with_mutations()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $withMutations = Dna::factory()->count(1)->withMutation()->create();
        $withoutMutations = Dna::factory()->count(2)->withoutMutation()->create();

        $this->assertDatabaseCount('dna_codes', 3);

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson([
                'count_mutations' => 1,
                'count_no_mutations' => 2,
                'ratio' => .33,
            ]);
    }

    /** @test */
    public function it_returns_only_results_without_mutations()
    {
        $user = $this->getAuthUser();

        $url = $this->getEndpoint();

        $withoutMutations = Dna::factory()->count(5)->withoutMutation()->create();

        $this->assertDatabaseCount('dna_codes', 5);

        $this->getJson($url)
            ->assertStatus(200)
            ->assertJson([
                'count_mutations' => 0,
                'count_no_mutations' => 5,
                'ratio' => 0,
            ]);
    }
}
