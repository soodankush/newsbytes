<?php

namespace Tests\Feature;

use Database\Factories\HashUrlFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Url;

class HashUrlTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Testing if the API is able to generate  URL hashing with the 201 status
     */
    public function test_create_hash_for_url(): void
    {
        $urlData = [
            "long_url"   => "https://documenter.getpostman.com/view/8610000/2s93mAUzwh",
            "single_use" =>  true
        ];

        $apiResponse = $this->postJson('/api/url', $urlData);
        $apiResponse->assertStatus(201);
        $apiResponse->assertJson([
            'success'   => true,
            'message'   => 'Hashed URL successfully generated'
        ]);
    }

    /**
     * Testing the post API for invalid request body for 422 status code
     * @return void
     */
    public function test_hash_not_created_when_improper_request_body(): void
    {
        $urlData = [
            "long_url"   => "https://documenter.getpostman.com/view/8610000/2s93mAUzwh"
        ];

        $apiResponse = $this->postJson('/api/url', $urlData);
        $apiResponse->assertStatus(422);
        $apiResponse->assertJson([
            'success'   => false,
        ]);
    }

    /**
     *
     * @return void
     */
    public function test_get_hash_url_data_report(): void
    {
        $urlData = Url::factory()->create();

        $urlDataResponse = $this->json('GET','/api/url-data/' . $urlData->hashed_url);

        $urlDataResponse->assertStatus(200)
            ->assertJson([
                'success'   => true,
                'message'   => 'Data fetched successfully',
                'data'      => [
                    'hashed_url'    => $urlData->hashed_url,
                    'long_url'      => $urlData->long_url,
                    'click_counts'  => $urlData->click_counts,
                    'single_use'    => $urlData->single_use,
                ]
            ]);
    }

    /**
     * Test to check if invalid hashed url is entered which generates 404 error
     * @return void
     */
    public function test_send_not_found_error_if_hashed_url_is_not_found(): void
    {
        $urlData = Url::factory()->create();

        $urlDataResponse = $this->json('GET','/api/url-data/gfdskjhu786');

        $urlDataResponse->assertStatus(404)
            ->assertJson([
                'success'   => false
            ]);
    }

    /**
     *
     * return void
     */
    public function test_to_check_if_hashed_url_redirects_to_mapped_url(): void
    {
        $urlData = Url::factory()->create();

        // Send a GET request to the hashed URL
        $redirectedResponse = $this->get(url($urlData->hashed_url));

        // Assert that the response status is HTTP 302 redirect
        $redirectedResponse->assertStatus(302);

        // Assert that the response redirects to the actual URL
        $redirectedResponse->assertRedirect($urlData->long_url);
    }

    /**
     *
     * return void
     */
    public function test_to_check_if_single_use_hashed_url_redirects_to_mapped_url_only_once(): void
    {
        $urlData = Url::factory()->create([
            'single_use'    => 1,
            'click_counts'  => 1
        ]);

        // Send a GET request to the hashed URL
        $this->get(url($urlData->hashed_url))
            ->assertStatus(500);
    }
}
