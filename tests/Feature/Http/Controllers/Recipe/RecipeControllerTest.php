<?php

namespace Tests\Feature\Http\Controllers\Recipe;

use App\Recipe;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Exercises the Recipe Controller functionality
 */
class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    /**
     * Tests that the controller returns all recipes contained in the database.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::where('email', Config::get('api.test_email'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('/api/recipe', ['authorization' => 'Bearer ' . $token]);
        $recipes = Recipe::all();

        $response->assertStatus(200);
        $response->assertExactJson($recipes->toArray());
    }
}
