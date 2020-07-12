<?php

namespace Tests\Feature\Http\Controllers\Ingredient;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Http\Jwt;
use Tests\TestCase;

/**
 * Exercises the Ingredient Controller functionality
 */
class IngredientControllerTest extends TestCase
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
     * Tests that the controller returns all ingredients contained in the database.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->get('/api/ingredient', $this->getAuthHeader());
        $ingredients = Ingredient::all();

        $response->assertStatus(200);
        $response->assertExactJson($ingredients->toArray());
    }

    /**
     * Tests that the controller return the ingredient object for the given id if valid (contained in the database).
     *
     * @param int $id Ingredient id to retrieve data for
     *
     * @return void
     * @dataProvider dataShow
     */
    public function testShow(int $id): void
    {
        $response = $this->get("/api/ingredient/{$id}", $this->getAuthHeader());
        $ingredient = Ingredient::with('recipes')->where('id', '=', $id)->get();

        $response->assertStatus(200);
        $response->assertExactJson($ingredient->toArray());
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
     * Tests that an ingredient can be deleted if the given id is valid
     *
     * @param int         $id                 Ingredient to delete
     * @param string|bool $expectedResult     Expected outcome
     * @param int         $expectedStatusCode Expected status code
     *
     * @return void
     * @dataProvider dataDelete
     */
    public function testDelete(int $id, $expectedResult, int $expectedStatusCode): void
    {
        $response = $this->delete("/api/ingredient/{$id}", [], $this->getAuthHeader());

        $this->assertEquals(0, (count(DB::table('ingredients')->get()->where('id', '=', $id))));
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
                'expected result' => 'Ingredient not found',
                'expecteded status code' => 404,
            ],
        ];
    }

    /**
     * Tests that an ingredient can be updated as expected
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $id = 1;
        $response = $this->put("/api/ingredient/{$id}", ['name' => 'test'], $this->getAuthHeader());

        $ingredientFromDb = DB::table('ingredients')->get()->where('id', '=', $id)->first();

        $this->assertSame($ingredientFromDb->id, (string) $id);
        $this->assertSame($ingredientFromDb->name, 'test');

        $response->assertStatus(200);
        $ingredientFromResponse = json_decode($response->getContent());
        $this->assertSame(1, $ingredientFromResponse->id);
        $this->assertSame('test', $ingredientFromResponse->name);
    }

    /**
     * Tests that an exception is thrown and that a redirect occurs when the given form request is invalid
     *
     * @param int           $id          Id of the ingredient to update
     * @param array         $requestBody Request sent from client
     *
     * @dataProvider dataUpdateException
     */
    public function testUpdateException(int $id, array $requestBody): void
    {
        $response = $this->put("/api/ingredient/{$id}", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();
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
            'name is not provided' => [
                'id' => 1,
                'request body' => [],
            ],
            'name is not long enough' => [
                'id' => 1,
                'request body' => ['name' => ''],
            ],
            'name is too long' => [
                'id' => 1,
                'request body' => ['name' => 'nplyjuzpjsiysjqtdabopsdbellawemqxxvpumjfnehkhqxfngvfimpsjqdjqltttavgnxtqjvtvnypjtjszjdjknmdusfzlsvvms '],
            ],
            'id does not exist' => [
                'id' => 99999,
                'request body' => [],
                'expecteded status code' => 302,
            ],
        ];
    }

    /**
     * Tests that an ingredient can be created as expected
     *
     * @return void
     */
    public function testStore(): void
    {
        $response = $this->post("/api/ingredient", ['name' => 'test'], $this->getAuthHeader());
        $ingredientFromDb = DB::table('ingredients')->get()->last();

        $this->assertSame($ingredientFromDb->name, 'test');

        $response->assertStatus(200);
        $ingredientFromResponse = json_decode($response->getContent());
        $this->assertSame('test', $ingredientFromResponse->name);
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
        $response = $this->post("/api/ingredient", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();
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
