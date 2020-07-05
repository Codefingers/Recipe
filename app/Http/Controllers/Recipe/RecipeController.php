<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipe;
use App\Http\Requests\UpdateRecipe;
use App\Recipe;
use Illuminate\Http\JsonResponse;

/**
 * Controller for interacting with Recipe entity type
 */
class RecipeController extends Controller
{
    /**
     * GET action on /api/recipe
     *
     * Returns all recipes
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Recipe::all());
    }

    /**
     * GET action on /api/recipe/{id}
     *
     * Returns a recipe for the given id along with its ingredients
     *
     * @param string $id Id to get recipe by
     *
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return response()->json(Recipe::with('ingredients')->where('id', '=', $id)->get());
    }

    /**
     * DELETE action on /api/recipe/{id}
     *
     * Deletes a recipe and the associated ingredients
     *
     * @param string $id Id to delete recipe and associated ingredients
     *
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        /** @var Recipe $recipe */
        $recipe = Recipe::find($id);
        if (!$recipe)
        {
            return response()->json('Recipe not found', 404);
        }

        $recipe->ingredients()->detach($id);
        return response()->json($recipe->delete());
    }

    /**
     * PUT action on /api/recipe/{id}
     *
     * Updates the given recipe with the given data
     *
     * @param string       $id Id of the recipe to update
     * @param UpdateRecipe $request Request sent from client
     *
     * @return JsonResponse
     */
    public function update(UpdateRecipe $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $recipe = Recipe::find($id);

        $recipe->id = $id;
        $recipe->name = $validated['name'];
        $recipe->update();

        return response()->json($recipe);
    }

    /**
     * POST action on /api/recipe
     *
     * Creates a new recipe with the given data
     *
     * @param StoreRecipe $request Request sent from client
     *
     * @return JsonResponse
     */
    public function store(StoreRecipe $request): JsonResponse
    {
        $validated = $request->validated();

        $recipe = new Recipe();
        $recipe->name = $validated['name'];

        if ($recipe->save()) {
            return response()->json($recipe);
        }

        return response()->json('', 500);

    }
}
