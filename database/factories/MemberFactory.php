<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->nama,
            'email' => $this->faker->email,
            'alamat' => $this->faker->alamat,
            'avatar' => $this->faker->avatar,
            'created_at' => $this->faker->created_at,
            'updated_at' => $this->faker->updated_at,
            'uuid' => $this->faker->uuid
        ];
    }
}
