<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Ingredient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIngredient;
use App\Http\Requests\UpdateIngredient;
use App\Models\Ingredient;
use Illuminate\Http\JsonResponse;

/**
 * Controller for interacting with Ingredient entity type
 */
class IngredientController extends Controller
{
    /**
     * GET action on /api/ingredient
     *
     * Returns all ingredients
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Ingredient::all());
    }

    /**
     * GET action on /api/ingredient/{id}
     *
     * Returns a ingredient for the given id along with its ingredients
     *
     * @param string $id Id to get ingredient by
     *
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);

        return response()->json($ingredient ? $ingredient->toArray() : []);
    }

    /**
     * DELETE action on /api/ingredient/{id}
     *
     * Deletes a ingredient and the associated ingredients
     *
     * @param string $id Id to delete ingredient and associated ingredients
     *
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);
        if (!$ingredient)
        {
            return response()->json('Ingredient not found', 404);
        }

        $ingredient->recipes()->detach($id);
        return response()->json($ingredient->delete());
    }

    /**
     * PUT action on /api/ingredient/{id}
     *
     * Updates the given ingredient with the given data
     *
     * @param string       $id Id of the ingredient to update
     * @param UpdateIngredient $request Request sent from client
     *
     * @return JsonResponse
     */
    public function update(UpdateIngredient $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $ingredient = Ingredient::find($id);
        if (is_null($ingredient)) {
            return response()->json('Ingredient not found', 404);
        }

        $ingredient->id = (int) $id;
        $ingredient->name = $validated['name'];
        $ingredient->update();

        return response()->json($ingredient);
    }

    /**
     * POST action on /api/ingredient
     *
     * Creates a new ingredient with the given data
     *
     * @param StoreIngredient $request Request sent from client
     *
     * @return JsonResponse
     */
    public function store(StoreIngredient $request): JsonResponse
    {
        $validated = $request->validated();

        $ingredient = new Ingredient();
        $ingredient->name = $validated['name'];

        if ($ingredient->save()) {
            return response()->json($ingredient);
        }

        return response()->json('', 500);

    }
}
