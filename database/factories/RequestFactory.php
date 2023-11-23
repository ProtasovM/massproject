<?php

namespace Database\Factories;

use App\Models\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Cache;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    protected $model = Request::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $answer = null;
        $answered_at = null;
        $created_at = $this->faker->dateTimeBetween('-2 month', '-1 month');
        if (rand(0,1)) {
            $answer = $this->faker->text(5000);
            $answered_at = $this->faker->dateTimeBetween('-1 month', 'now');
        }
        $respondents = $this->getRespondents();

        return [
            'author_id' => rand(1, 1000),
            'status' => $answer ? Request::RESOLVED_STATUS
                : Request::ACTIVE_STATUS,
            'message' => $this->faker->text(5000),
            'respondent_id' => $respondents->random()->id,
            'answer' => $answer,
            'answered_at' => $answered_at,
            'created_at' => $created_at,
            'updated_at' => $answered_at ? $answered_at : $created_at,
        ];
    }

    private function getRespondents(): Collection
    {
        $respondents = Cache::get('respondents');
        if (!$respondents) {
            $respondents = User::whereHas('roles', function ($builder) {
                $builder->where('name', Role::MODERATOR_TYPE);
            })->get(['id']);
            Cache::put('respondents', $respondents, 30);
        }
        return $respondents;
    }
}
