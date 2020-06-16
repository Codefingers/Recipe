<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    public function index()
    {
        return response('test');
    }
}
