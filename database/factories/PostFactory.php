<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence;

        return [
            'title' => $title,
            'metadata' => $this->getMetaData(),
            'slug' => Str::slug($title),
            'content' => fake()->text,
        ];
    }

    protected function getMetaData(): array
    {
        return [
            'author' => fake()->name,
            'image' => File::query()->pluck('uuid')[fake()
                ->numberBetween(1, File::query()->count() - 1)],
        ];
    }
}
