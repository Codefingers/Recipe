<?php
declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Recipe
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ingredient[] $ingredients
 * @property-read int|null $ingredients_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recipe whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Step[] $steps
 * @property-read int|null $steps_count
 */
class Recipe extends Model
{
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }
}
