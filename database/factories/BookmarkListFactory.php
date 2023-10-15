<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookmarkList>
 */
class BookmarkListFactory extends Factory
{
    protected $user_id = 0;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->user_id++;
        return [
            'name' => 'My List',
            'user_id' => $this->user_id,
        ];
    }
}
