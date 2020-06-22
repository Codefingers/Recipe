<?php

namespace Tests\Feature\Http\Controllers\Recipe;

use App\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $response = $this->get('/api/recipe', ['authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTU5Mjc3NDEyOSwiZXhwIjoxNTkyNzc3NzI5LCJuYmYiOjE1OTI3NzQxMjksImp0aSI6IkV4VzVrd2NCSWZpdUxPdmIiLCJzdWIiOm51bGwsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.nue1tKZXinQKv0BS0u0NsC8W7wjS9W8mBBm3QLGA12wv']);
        $recipes = Recipe::all();

        $response->assertStatus(200);
        $response->assertExactJson($recipes->toArray());
    }
}
