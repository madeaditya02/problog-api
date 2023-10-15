<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    private $job = ['Front End Web Developer', 'Back End Web Developer', 'Android Developer', 'Mobile App Developer', 'Ios Developer', 'Cloud Developer', 'Game Developer', 'Machine Learning Engineers', 'Data Scientist', 'DevOps', 'Student'];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail();
        $name = $this->faker->name();
        $username = $this->faker->unique()->userName();
        // $name_str = str_replace(' ', '+', $name);
        return [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'job' => $this->job[rand(0, count($this->job)-1)],
            'location' => 'Jakarta, Indonesia',
            'bio' => $this->faker->realText(100),
            // 'picture' => 'https://gravatar.com/avatar/' . md5($email),
            'picture' => "https://ui-avatars.com/api/?name=$name&background=random",
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'github_link' => "https://github.com/$username",
            'instagram_link' => "https://instagram.com/$username",
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
