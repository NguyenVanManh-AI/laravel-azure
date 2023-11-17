<?php

namespace Database\Seeders;

use App\Models\User;
use App\Repositories\InforHospitalRepository;
use App\Repositories\InforUserRepository;
use App\Repositories\TimeWorkRepository;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // hospital
        $pathFolder = 'public/storage/image/avatars/hospitals';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $pathFolderHospital = 'public/storage/image/covers/hospitals';
        if (!File::exists($pathFolderHospital)) {
            File::makeDirectory($pathFolderHospital, 0755, true);
        }

        $hospitals = [
            [
                'email' => 'benhviengiadinh@yopmail.com',
                'username' => 'benhviengiadinh',
                'name' => 'Bệnh viện gia đình',
                'address' => 'Đà Nẵng - Việt Nam',
                'phone' => '0971231233',
            ],
            [
                'email' => 'benhvienkimkhanh@yopmail.com',
                'username' => 'benhvienkimkhanh',
                'name' => 'Bệnh viện Kim Khánh',
                'address' => 'Liên Chiểu - Đà Nẵng - Việt Nam',
                'phone' => '0977864372',
            ],
            [
                'email' => 'benhvienvietnhat@yopmail.com',
                'username' => 'benhvienvietnhat',
                'name' => 'Bệnh viện Việt Nhật',
                'address' => 'Hòa Khánh - Đà Nẵng - Việt Nam',
                'phone' => '0977864999',
            ],
            [
                'email' => 'benhvienviethan@yopmail.com',
                'username' => 'benhvienviethan',
                'name' => 'Bệnh viện Việt Hàn',
                'address' => 'Cẩm Lệ - Đà Nẵng - Việt Nam',
                'phone' => '0977864669',
            ],
            [
                'email' => 'benhvienvietphap@yopmail.com',
                'username' => 'benhvienvietphap',
                'name' => 'Bệnh viện Việt Pháp',
                'address' => 'Đà Nẵng - Việt Nam',
                'phone' => '0977864569',
            ],
            [
                'email' => 'benhvienhanoi@yopmail.com',
                'username' => 'benhvienhanoi',
                'name' => 'Bệnh viện Hà Nội',
                'address' => 'Đà Nẵng - Việt Nam',
                'phone' => '0977864599',
            ],
        ];

        $pathFolder = 'storage/app/public/image/avatars/hospitals/';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $pathFolderHospital = 'storage/app/public/image/covers/hospitals/';
        if (!File::exists($pathFolderHospital)) {
            File::makeDirectory($pathFolderHospital, 0755, true);
        }
        foreach ($hospitals as $index => $hospital) {
            try {
                while (true) {
                    $client = new Client;
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $avatar = $pathFolder . $nameImage;
                    if (file_put_contents($avatar, $imageContent)) {
                        $data = array_merge(
                            $hospital,
                            [
                                'role' => 'hospital',
                                'password' => Hash::make('123456'),
                                'avatar' => 'storage/image/avatars/hospitals/' . $nameImage,
                                'is_accept' => 1,
                                'token_verify_email' => null,
                                'email_verified_at' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                        $user = User::create($data);

                        // infor_hospital
                        while (true) {
                            $client = new Client;
                            $responseCover = $client->get('https://picsum.photos/200/200');
                            $imageContentCover = $responseCover->getBody()->getContents();
                            $nameImageCover = uniqid() . '.jpg';
                            $coverHospital = $pathFolderHospital . $nameImageCover;
                            if (file_put_contents($coverHospital, $imageContentCover)) {
                                $data = [
                                    'id_hospital' => $user->id,
                                    'cover_hospital' => 'storage/image/covers/hospitals/' . $nameImageCover,
                                    'province_code' => random_int(1, 63),
                                    'infrastructure' => json_encode(['Máy nội soi', 'Giường bệnh', 'Phòng xét nghiệm', 'Máy chụp phim X-Quang kỹ thuật số', 'Chụp cắt lớp vi tính (Chụp CT)', 'Siêu âm', 'Máy chụp nhũ ảnh', 'Máy khám tân tiến']),
                                    'description' => 'Bệnh viện tốt, đa chuyên khoa, dịch vụ giá cả hợp lí .',
                                    'location' => json_encode([19, 29]),
                                    'search_number' => random_int(0, 300),
                                ];
                                $inforUser = InforHospitalRepository::createHospital($data);
                                break;
                            }
                        }
                        // infor_hospital

                        // addTimeWork
                        $timeDefault = [
                            'enable' => true,
                            'morning' => [
                                'enable' => true,
                                'time' => ['7:30', '11:30'],
                            ],
                            'afternoon' => [
                                'enable' => true,
                                'time' => ['13:30', '17:30'],
                            ],
                            'night' => [
                                'enable' => true,
                                'time' => ['18:00', '20:00'],
                            ],
                        ];

                        $dataTimeWork = [
                            'id_hospital' => $user->id,
                            'enable' => true,
                            'note' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'times' => json_encode([
                                'monday' => $timeDefault,
                                'tuesday' => $timeDefault,
                                'wednesday' => $timeDefault,
                                'thursday' => $timeDefault,
                                'friday' => $timeDefault,
                                'saturday' => $timeDefault,
                                'sunday' => $timeDefault,
                            ]),
                        ];
                        $timeWork = TimeWorkRepository::createTimeWork($dataTimeWork);
                        $timeWork->times = json_decode($timeWork->times);
                        // addTimeWork
                        break;
                    }
                }
            } catch (\Exception $e) {
            }
        }

        // user
        $pathFolder = 'public/storage/image/avatars/users';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }
        $users = [
            [
                'email' => 'nhathoang999@yopmail.com',
                'username' => 'nhathoang999',
                'name' => 'Nguyễn Phan Nhật Hoàng',
                'address' => 'Hải Châu - Việt Nam',
                'phone' => '0971231233',
            ],
            [
                'email' => 'myduyen1239@yopmail.com',
                'username' => 'myduyen1239',
                'name' => 'Nguyễn Trần Mỹ Duyên',
                'address' => 'Nghệ An - Việt Nam',
                'phone' => '0975331233',
            ],
            [
                'email' => 'phiduy999@yopmail.com',
                'username' => 'phiduy999',
                'name' => 'Lê Phi Duy',
                'address' => 'Đồng Nai - Việt Nam',
                'phone' => '0912531233',
            ],
            [
                'email' => 'vannghia99@yopmail.com',
                'username' => 'vannghia99',
                'name' => 'Nguyễn Văn Nghĩa',
                'address' => 'Tuyên Quang - Việt Nam',
                'phone' => '0971231233',
            ],
            [
                'email' => 'quangthai99@yopmail.com',
                'username' => 'quangthai99',
                'name' => 'Trần Quang Thái',
                'address' => 'Huế - Việt Nam',
                'phone' => '0971231123',
            ],
            [
                'email' => 'khanhlinh999@yopmail.com',
                'username' => 'khanhlinh999',
                'name' => 'Nguyễn Thị Khánh Linh',
                'address' => 'Quảng Nam - Việt Nam',
                'phone' => '0971231233',
            ],
        ];

        $pathFolder = 'storage/app/public/image/avatars/users/';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }
        foreach ($users as $index => $user) {
            try {
                while (true) {
                    $client = new Client;
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $avatar = $pathFolder . $nameImage;
                    if (file_put_contents($avatar, $imageContent)) {
                        $data = array_merge(
                            $user,
                            [
                                'role' => 'user',
                                'password' => Hash::make('123456'),
                                'avatar' => 'storage/image/avatars/users/' . $nameImage,
                                'is_accept' => 1,
                                'token_verify_email' => null,
                                'email_verified_at' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                        $new_user = User::create($data);

                        // infor_user
                        $data = [
                            'id_user' => $new_user->id,
                            'date_of_birth' => date('Y-m-d', mt_rand(strtotime('2000-01-01'), strtotime('2002-12-29'))),
                            'google_id' => null,
                            'facebook_id' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'gender' => random_int(0, 1),
                        ];
                        $inforUser = InforUserRepository::createInforUser($data);
                        // infor_user

                        break;
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }
}
