<?php

namespace Tests\Feature\Http\Controllers\Recipe;

use App\Models\Recipe;
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

        $this->assertEquals(0, (count(DB::table('recipes')->get()->where('id', '=', $id))));
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
     * Tests that a recipe can be updated as expected
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $id = 1;
        $response = $this->put("/api/recipe/{$id}", ['name' => 'test'], $this->getAuthHeader());

        $recipeFromDb = DB::table('recipes')->get()->where('id', '=', $id)->first();

        $this->assertSame($recipeFromDb->id, (string) $id);
        $this->assertSame($recipeFromDb->name, 'test');

        $response->assertStatus(200);
        $recipeFromResponse = json_decode($response->getContent());
        $this->assertSame(1, $recipeFromResponse->id);
        $this->assertSame('test', $recipeFromResponse->name);
    }

    /**
     * Tests that an exception is thrown and that a redirect occurs when the given form request is invalid
     *
     * @param int           $id          Id of the recipe to update
     * @param array         $requestBody Request sent from client
     *
     * @dataProvider dataUpdateException
     */
    public function testUpdateException(int $id, array $requestBody): void
    {
        $response = $this->put("/api/recipe/{$id}", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();
        $response->assertStatus(302);
    }

    /**
     * Dataprovider for testUpdateException
     *
     * @return array
     */
    public function dataUpdateException(): array
    {
        return [
            'name is not long enough' => [
                'id' => 1,
                'request body' => ['id' => 1, 'name' => ''],
            ],
            'name is too long' => [
                'id' => 1,
                'request body' => ['id' => 1, 'name' => 'nplyjuzpjsiysjqtdabopsdbellawemqxxvpumjfnehkhqxfngvfimpsjqdjqltttavgnxtqjvtvnypjtjszjdjknmdusfzlsvvms '],
            ],
            'id does not exist' => [
                'id' => 99999,
                'request body' => [],
                'expecteded status code' => 302,
            ],
        ];
    }

    /**
     * Tests that a recipe can be created as expected
     *
     * @return void
     */
    public function testStore(): void
    {
        $response = $this->post("/api/recipe", ['name' => 'test', 'duration' => 120, 'difficulty' => 3], $this->getAuthHeader());
        $recipeFromDb = DB::table('recipes')->get()->last();

        $this->assertSame($recipeFromDb->name, 'test');

        $response->assertStatus(200);
        $recipeFromResponse = json_decode($response->getContent());
        $this->assertSame('test', $recipeFromResponse->name);
    }

    /**
     * Tests that an exception is thrown and that a redirect occurs when the given form request is invalid
     *
     * @param array $requestBody Request sent from client
     *
     * @dataProvider dataStoreException
     */
    public function testStoreException(array $requestBody): void
    {
        $response = $this->post("/api/recipe", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();
        $response->assertStatus(302);
    }

    /**
     * Dataprovider for testStoreException
     *
     * @return array
     */
    public function dataStoreException(): array
    {
        return [
            'name is not provided' => [
                'request body' => [],
            ],
            'name is not long enough' => [
                'request body' => ['name' => ''],
            ],
            'name is too long' => [
                'request body' => ['name' => 'nplyjuzpjsiysjqtdabopsdbellawemqxxvpumjfnehkhqxfngvfimpsjqdjqltttavgnxtqjvtvnypjtjszjdjknmdusfzlsvvms '],
            ],
        ];
    }
}
