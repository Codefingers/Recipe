<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Step;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStep;
use App\Http\Requests\UpdateStep;
use App\Models\Step;
use Illuminate\Http\JsonResponse;

/**
 * Controller for interacting with Step entity type
 */
class StepController extends Controller
{
    /**
     * GET action on /api/step
     *
     * Returns all steps
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Step::all());
    }

    /**
     * GET action on /api/step/{id}
     *
     * Returns a step for the given id along with its steps
     *
     * @param string $id Id to get step by
     *
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $step = Step::find($id);

        return response()->json($step ? $step->toArray() : []);
    }

    /**
     * DELETE action on /api/step/{id}
     *
     * Deletes a step and the associated steps
     *
     * @param string $id Id to delete step and associated steps
     *
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $step = Step::find($id);
        if (!$step)
        {
            return response()->json('Step not found', 404);
        }

        return response()->json($step->delete());
    }

    /**
     * PUT action on /api/step/{id}
     *
     * Updates the given step with the given data
     *
     * @param string       $id      Id of the step to update
     * @param UpdateStep   $request Request sent from client
     *
     * @return JsonResponse
     */
    public function update(UpdateStep $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $step = Step::find($id);
        if (is_null($step)) {
            return response()->json('Step not found', 404);
        }

        $step->id = (int) $id;
        $step->step = $validated['step'];
        $step->order = (int) $validated['order'];
        $step->recipe_id = (int) $validated['recipe_id'];
        $step->update();

        return response()->json($step);
    }

    /**
     * POST action on /api/step
     *
     * Creates a new step with the given data
     *
     * @param StoreStep $request Request sent from client
     *
     * @return JsonResponse
     */
    public function store(StoreStep $request): JsonResponse
    {
        $validated = $request->validated();

        $step = new Step();
        $step->step = $validated['step'];
        $step->order = (int) $validated['order'];
        $step->recipe_id = (int) $validated['recipe_id'];

        if ($step->save()) {
            return response()->json($step);
        }

        return response()->json('', 500);

    }
}
