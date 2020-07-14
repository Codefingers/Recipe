<?php

namespace Tests\Feature\Http\Controllers\Step;

use App\Models\Step;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Http\Jwt;
use Tests\TestCase;

/**
 * Exercises the Step Controller functionality
 */
class StepControllerTest extends TestCase
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
     * Tests that the controller returns all steps contained in the database.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->get('/api/step', $this->getAuthHeader());
        $steps = Step::all();

        $response->assertStatus(200);
        $response->assertExactJson($steps->toArray());
    }

    /**
     * Tests that the controller return the step object for the given id if valid (contained in the database).
     *
     * @param int $id Step id to retrieve data for
     *
     * @return void
     * @dataProvider dataShow
     */
    public function testShow(int $id): void
    {
        $response = $this->get("/api/step/{$id}", $this->getAuthHeader());
        $step = Step::find($id);

        $response->assertStatus(200);
        $response->assertExactJson($step ? $step->toArray() : []);
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
     * Tests that an step can be deleted if the given id is valid
     *
     * @param int         $id                 Step to delete
     * @param string|bool $expectedResult     Expected outcome
     * @param int         $expectedStatusCode Expected status code
     *
     * @return void
     * @dataProvider dataDelete
     */
    public function testDelete(int $id, $expectedResult, int $expectedStatusCode): void
    {
        $response = $this->delete("/api/step/{$id}", [], $this->getAuthHeader());

        $this->assertEquals(0, (count(DB::table('steps')->get()->where('id', '=', $id))));
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
                'expected result' => 'Step not found',
                'expecteded status code' => 404,
            ],
        ];
    }

    /**
     * Tests that an step can be updated as expected
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $id = 1;
        $response = $this->put(
            "/api/step/{$id}",
            ['step' => 'test', 'order' => '1', 'recipe_id' => '1'],
            $this->getAuthHeader()
        );

        $stepFromDb = DB::table('steps')->get()->where('id', '=', $id)->first();

        $this->assertSame($stepFromDb->id, (string) $id);
        $this->assertSame($stepFromDb->step, 'test');
        $this->assertSame($stepFromDb->order, '1');
        $this->assertSame($stepFromDb->recipe_id, '1');

        $response->assertStatus(200);
        $stepFromResponse = json_decode($response->getContent());
        $this->assertSame(1, $stepFromResponse->id);
        $this->assertSame('test', $stepFromResponse->step);
        $this->assertSame(1, $stepFromResponse->order);
        $this->assertSame(1, $stepFromResponse->recipe_id);
    }

    /**
     * Tests that an exception is thrown and that a redirect occurs when the given form request is invalid
     *
     * @param int           $id          Id of the step to update
     * @param array         $requestBody Request sent from client
     *
     * @dataProvider dataUpdateException
     */
    public function testUpdateException(int $id, array $requestBody): void
    {
        $response = $this->put("/api/step/{$id}", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();
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
            'step and order is not provided' => [
                'id' => 1,
                'request body' => [],
            ],
            'step is not long enough' => [
                'id' => 1,
                'request body' => ['step' => ''],
            ],
            'step is too long' => [
                'id' => 1,
                'request body' => ['step' => 'nplyjuzpjsiysjqtdabopsdbellawemqxxvpumjfnehkhqxfngvfimpsjqdjqltttavgnxtqjvtvnypjtjszjdjknmdusfzlsvvms '],
            ],
            'id does not exist' => [
                'id' => 99999,
                'request body' => [],
                'expecteded status code' => 302,
            ],
            'order is a word' => [
                'id' => 1,
                'request body' => ['step' => 'nplyjuzpjsiysj', 'order' => 'abc'],
            ],
            'recipe id does not exist' => [
                'id' => 1,
                'request body' => ['step' => 'nplyjuzpjsiysj', 'order' => '1', 'recipe_id' => 99999],
            ]
        ];
    }

    /**
     * Tests that an step can be created as expected
     *
     * @return void
     */
    public function testStore(): void
    {
        $response = $this->post(
            "/api/step",
            ['step' => 'test', 'order' => '1', 'recipe_id' => '1'],
            $this->getAuthHeader()
        );
        $stepFromDb = DB::table('steps')->get()->last();

        $this->assertSame('test', $stepFromDb->step);
        $this->assertSame('1', $stepFromDb->order);
        $this->assertSame('1', $stepFromDb->recipe_id);

        $response->assertStatus(200);
        $stepFromResponse = json_decode($response->getContent());
        $this->assertSame('test', $stepFromResponse->step);
        $this->assertSame(1, $stepFromResponse->order);
        $this->assertSame(1, $stepFromResponse->recipe_id);
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
        $response = $this->post("/api/step", $requestBody, $this->getAuthHeader())->assertSessionHasErrors();
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
            'step is not provided' => [
                'request body' => [],
            ],
            'step is not long enough' => [
                'request body' => ['step' => ''],
            ],
            'step is too long' => [
                'request body' => ['step' => 'nplyjuzpjsiysjqtdabopsdbellawemqxxvpumjfnehkhqxfngvfimpsjqdjqltttavgnxtqjvtvnypjtjszjdjknmdusfzlsvvms '],
            ],
            'order is a word' => [
                'request body' => ['step' => 'nplyjuzpjsiysj', 'order' => 'abc'],
            ],
            'recipe id does not exist' => [
                'request body' => ['step' => 'nplyjuzpjsiysj', 'order' => '1', 'recipe_id' => 99999],
            ]
        ];
    }
}
