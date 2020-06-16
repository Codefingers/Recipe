<?php

namespace Tests\Feature\Http\Controllers\Recipe;

use App\Recipe;
use Tests\TestCase;

/**
 * Exercises the Recipe Controller functionality
 */
class RecipeControllerTest extends TestCase
{
    /**
     * Tests that the controller returns all recipes contained in the database.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/api/recipe');
        $recipes = Recipe::all();

        $response->assertStatus(200);
        $response->assertExactJson($recipes->toArray());
    }
}
