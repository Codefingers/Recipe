<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SorryController extends Controller
{
    /**
     * Returns a sorry request
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(['Sorry, you are not authenticated'], 403);
    }
}
