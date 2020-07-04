<?php
declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recipe
 *
 * @property int $id
 * @property string $name
 */
class Recipe extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
}
