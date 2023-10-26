<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class FakeImageFactory extends Factory
{
    public function definition()
    {
        // Đừng có lấy ảnh ở đây
    }

    // Phương thức để tạo và trả về tên ảnh
    public function createThumbnailCategory()
    {
        $pathFolder = 'public/storage/image/thumbnail/categories';
        $nameImage = $this->faker->image($pathFolder, 200, 200, null, false);

        return $nameImage;
    }

    public function createThumbnailDepartment()
    {
        $pathFolder = 'public/storage/image/thumbnail/departments';
        $nameImage = $this->faker->image($pathFolder, 200, 200, null, false);

        return $nameImage;
    }

    public function createAvatarDoctor()
    {
        $pathFolder = 'storage/image/avatars/doctors';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }
        $nameImage = $this->faker->image($pathFolder, 200, 200, null, false);

        return $nameImage;
    }
}
