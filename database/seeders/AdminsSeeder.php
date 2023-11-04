<?php

namespace Database\Seeders;

use App\Models\Admin;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pathFolder = 'public/storage/image/avatars/admins';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $admins = [
            [
                'email' => 'vanmanh.dut@gmail.com',
                'name' => 'Nguyễn Văn Mạnh',
                'date_of_birth' => '2001-08-29',
                'address' => 'Phú Đa - Phú Vang - Thừa Thiên Huế',
                'phone' => '0971404372',
                'gender' => 0,
                'role' => 'manager',
            ],

            [
                'email' => 'thuyduong9@yopmail.com',
                'name' => 'Trần Thị Thùy Dương',
                'date_of_birth' => '2002-06-26',
                'address' => 'Đà Lạt - Việt Nam',
                'phone' => '0977864372',
                'gender' => 1,
                'role' => 'superadmin',
            ],
            [
                'email' => 'myandth99@yopmail.com',
                'name' => 'Nguyễn Thị Mỹ An',
                'date_of_birth' => '2002-05-15',
                'address' => 'Đà Nẵng - Việt Nam',
                'phone' => '0973404399',
                'gender' => 1,
                'role' => 'superadmin',
            ],
            [
                'email' => 'vanvu999@yopmail.com',
                'name' => 'Trần Văn Vũ',
                'date_of_birth' => '2002-01-11',
                'address' => 'Hồ Chí Minh - Việt Nam',
                'phone' => '0986404356',
                'gender' => 0,
                'role' => 'superadmin',
            ],
            [
                'email' => 'phanvanhoang99@yopmail.com',
                'name' => 'Phan Văn Hoàng',
                'date_of_birth' => '2002-09-10',
                'address' => 'Hà Nội - Việt Nam',
                'phone' => '0971404399',
                'gender' => 0,
                'role' => 'admin',
            ],
            [
                'email' => 'nganhim@yopmail.com',
                'name' => 'Ngân Hiim',
                'date_of_birth' => '2002-06-06',
                'address' => 'Quảng Trị - Việt Nam',
                'phone' => '0971455759',
                'gender' => 1,
                'role' => 'admin',
            ],
            [
                'email' => 'kimthi@yopmail.com',
                'name' => 'Nguyễn Thị Kim Thi',
                'date_of_birth' => '2002-11-12',
                'address' => 'Nghệ An - Việt Nam',
                'phone' => '0971366399',
                'gender' => 1,
                'role' => 'admin',
            ],

        ];

        foreach ($admins as $index => $admin) {
            try {
                $pathFolder = 'storage/app/public/image/avatars/admins/';
                if (!File::exists($pathFolder)) {
                    File::makeDirectory($pathFolder, 0755, true);
                }
                $client = new Client;
                while (true) {
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $avatar = $pathFolder . $nameImage;
                    if (file_put_contents($avatar, $imageContent)) {
                        $data = array_merge(
                            $admin,
                            [
                                'password' => Hash::make('123456'),
                                'avatar' => 'storage/image/avatars/admins/' . $nameImage,
                                'token_verify_email' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                                'email_verified_at' => now(),
                            ]
                        );
                        Admin::create($data);
                        break;
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }
}
