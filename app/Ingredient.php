<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingredient
 *
 * @property int $id
 * @property string $name
 */
class Ingredient extends Model
{
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}
