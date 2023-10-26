<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Admin::class;

    public function definition()
    {
        $pathFolder = 'public/storage/image/avatars/admins';
        if (!File::exists($pathFolder)) { // chưa có folder thì tạo
            File::makeDirectory($pathFolder, 0755, true);
        }

        $nameImage = $this->faker->image($pathFolder, 200, 200, null, false);
        while (!$nameImage) { // đảm bảo chắc chắc chắn phải có được ảnh
            $nameImage = $this->faker->image($pathFolder, 200, 200, null, false);
        }

        return [
            'email' => 'vanmanh.dut@gmail.com',
            'password' => Hash::make('vanmanh.dut'),
            'name' => 'Nguyễn Văn Mạnh',
            'date_of_birth' => '2001-08-29',
            'address' => 'Phú Đa - Phú Vang - Thừa Thiên Huế',
            'phone' => '0971404372',
            'gender' => 1,
            'avatar' => 'storage/image/avatars/admins/' . $nameImage,
            'role' => 'manager',
            'token_verify_email' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
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
