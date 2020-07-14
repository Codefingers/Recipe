<?php
declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Step
 *
 * @property int $id
 * @property string $step
 * @property int $order
 * @property int $recipe_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Recipe $recipe
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step whereRecipeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Step whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Step extends Model
{
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
