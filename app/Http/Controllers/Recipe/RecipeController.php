<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Recipe;

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
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index()
    {
        return response(Recipe::all());
    }

    public function show(string $id)
    {
        $recipe = Recipe::with('ingredients')->where('id', '=', $id)->get();
        return response($recipe);
    }
}
