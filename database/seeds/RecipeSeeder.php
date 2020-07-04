<?php

use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipes = factory(App\Recipe::class, 50)->create();
        $ingredients = factory(App\Ingredient::class, 50)->create();

        $recipes->each(function (App\Recipe $recipe) use ($ingredients) {
            $recipe->ingredients()->attach(
                $ingredients->random(rand(1,4))->pluck('id')->toArray()
            );
        });
    }
}
