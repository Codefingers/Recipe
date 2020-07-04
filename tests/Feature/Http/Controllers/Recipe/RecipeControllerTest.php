<?php

namespace Tests\Feature\Http\Controllers\Recipe;

use App\Http\Requests\UpdateRecipe;
use App\Recipe;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Http\Jwt;
use Tests\TestCase;

/**
 * Exercises the Recipe Controller functionality
 */
class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;
    use Jwt;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Tests that the controller returns all recipes contained in the database.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->get('/api/recipe', $this->getAuthHeader());
        $recipes = Recipe::all();

        $response->assertStatus(200);
        $response->assertExactJson($recipes->toArray());
    }

    /**
     * Tests that the controller return the recipe object for the given id if valid (contained in the database).
     *
     * @param int $id Recipe id to retrieve data for
     *
     * @return void
     * @dataProvider dataShow
     */
    public function testShow(int $id): void
    {
        $response = $this->get("/api/recipe/{$id}", $this->getAuthHeader());
        $recipe = Recipe::with('ingredients')->where('id', '=', $id)->get();

        $response->assertStatus(200);
        $response->assertExactJson($recipe->toArray());
    }

    /**
     * Dataprovider for testShow
     *
     * @return array
     */
    public function dataShow(): array
    {
        return [
            'valid id' => [
                'id' => 1,
            ],
            'record does not exist for given id' => [
                'id' => 99999,
            ],
        ];
    }

    /**
     * Tests that a recipe can be deleted if the given id is valid
     *
     * @param int         $id                 Recipe to delete
     * @param string|bool $expectedResult     Expected outcome
     * @param int         $expectedStatusCode Expected status code
     *
     * @return void
     * @dataProvider dataDelete
     */
    public function testDelete(int $id, $expectedResult, int $expectedStatusCode): void
    {
        $response = $this->delete("/api/recipe/{$id}", [], $this->getAuthHeader());

        $response->assertStatus($expectedStatusCode);
        $response->assertExactJson([$expectedResult]);
    }

    /**
     * Dataprovider for testDelete
     *
     * @return array
     */
    public function dataDelete(): array
    {
        return [
            'valid id' => [
                'id' => 1,
                'expected result' => true,
                'expecteded status code' => 200,
            ],
            'record does not exist for given id' => [
                'id' => 99999,
                'expected result' => 'Recipe not found',
                'expecteded status code' => 404,
            ],
        ];
    }

    /**
     *
     */
    public function testUpdate(): void
    {
        $response = $this->put("/api/recipe/1", ['name' => 'test'], $this->getAuthHeader());

        $response->assertStatus(200);
        $recipe = json_decode($response->getContent());
        $this->assertSame(1, $recipe->id);
        $this->assertSame('test', $recipe->name);
    }

    /**
     * Dataprovider for testUpdate
     *
     * @return array
     */
    public function dataUpdate(): array
    {
        return [
            'valid id' => [
                'id' => 1,
                'request body' => ['name' => 'test '],
                'expected result' => true,
                'expecteded status code' => 200,
            ],
        ];
    }

    /**
     *
     *
     * @param int           $id
     * @param array         $requestBody
     * @param string|Recipe $expectedResult
     * @param int           $expectedStatusCode
     *
     * @dataProvider dataUpdateException
     */
    public function testUpdateException(int $id, array $requestBody, int $expectedStatusCode): void
    {
        $response = $this->put("/api/recipe/{$id}", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();

        $response->assertStatus($expectedStatusCode);
    }

    /**
     * Dataprovider for testUpdateException
     *
     * @return array
     */
    public function dataUpdateException(): array
    {
        return [
            'name is not provided' => [
                'id' => 1,
                'request body' => [],
                'expecteded status code' => 302,
            ],
            'name is not long enough' => [
                'id' => 1,
                'request body' => ['name' => ''],
                'expecteded status code' => 302,
            ],
            'name is too long' => [
                'id' => 1,
                'request body' => ['name' => 'nplyjuzpjsiysjqtdabopsdbellawemqxxvpumjfnehkhqxfngvfimpsjqdjqltttavgnxtqjvtvnypjtjszjdjknmdusfzlsvvms '],
                'expecteded status code' => 302,
            ],
            'id does not exist' => [
                'id' => 99999,
                'request body' => [],
                'expecteded status code' => 302,
            ],
        ];
    }
}
