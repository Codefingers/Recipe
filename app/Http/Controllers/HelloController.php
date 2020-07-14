<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

/**
 * Class HelloController
 *
 * Public endpoint for displaying the app version and the environment being run on
 */
class HelloController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'name' => Config::get('app.name'),
            'version' => Config::get('api.version')
        ]);
    }
}
