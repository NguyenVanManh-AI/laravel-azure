<?php

namespace Database\Seeders;

use App\Models\HospitalDepartment;
use App\Models\User;
use App\Repositories\InforDoctorRepository;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DoctorsSeeder extends Seeder
{
    public function run()
    {
        try {
            // doctor
            $pathFolder = 'public/storage/image/avatars/doctors';
            if (!File::exists($pathFolder)) {
                File::makeDirectory($pathFolder, 0755, true);
            }
            $doctors = [
                [
                    'email' => 'bacsinguyen9@yopmail.com',
                    'username' => 'bacsinguyen9',
                    'name' => 'Bác sĩ Thanh Nguyên',
                    'address' => 'Tuyên Quang - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsitoan99@yopmail.com',
                    'username' => 'bacsitoan99',
                    'name' => 'Bác sĩ Đại Toàn',
                    'address' => 'Tuyên Quang - Việt Nam',
                    'phone' => '0971786233',
                ],
                [
                    'email' => 'bacsithusuong@yopmail.com',
                    'username' => 'bacsithusuong',
                    'name' => 'Bác sĩ Thu Sương',
                    'address' => 'Vinh - Nghệ An - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsiphanhai@yopmail.com',
                    'username' => 'bacsiphanhai',
                    'name' => 'Bác sĩ Phan Hải',
                    'address' => 'Nghệ An - Việt Nam',
                    'phone' => '0452656233',
                ],
                [
                    'email' => 'bacsibacsihuong@yopmail.com',
                    'username' => 'bacsibacsihuong',
                    'name' => 'Bác sĩ Quỳnh Hương',
                    'address' => 'Vinh - Nghệ An - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsithang@yopmail.com',
                    'username' => 'bacsithang',
                    'name' => 'Bác sĩ Huỳnh Công Thắng',
                    'address' => 'Quảng Nam - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsihieu@yopmail.com',
                    'username' => 'bacsihieu',
                    'name' => 'Bác sĩ Nguyễn Văn Hiệu',
                    'address' => 'Thủy Châu - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsian@yopmail.com',
                    'username' => 'bacsian',
                    'name' => 'Bác sĩ Nguyễn Văn An',
                    'address' => 'Vinh - Nghệ An - Việt Nam',
                    'phone' => '0971454523',
                ],
                [
                    'email' => 'bacsitantai@yopmail.com',
                    'username' => 'bacsitantai',
                    'name' => 'Bác sĩ Nguyễn Tấn Tài',
                    'address' => 'Hải Châu - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsisongtoan@yopmail.com',
                    'username' => 'bacsisongtoan',
                    'name' => 'Bác sĩ Nguyễn Văn Song Toàn',
                    'address' => 'Vinh - Nghệ An - Việt Nam',
                    'phone' => '0971445623',
                ],
                [
                    'email' => 'bacsingochoai@yopmail.com',
                    'username' => 'bacsingochoai',
                    'name' => 'Bác sĩ Nguyễn Thị Ngọc Hoài',
                    'address' => 'Quảng Nam - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsinhung@yopmail.com',
                    'username' => 'bacsinhung',
                    'name' => 'Bác sĩ Nguyễn Thị Nhung',
                    'address' => 'Vinh - Nghệ An - Việt Nam',
                    'phone' => '0971445623',
                ],
                [
                    'email' => 'bacsilien@yopmail.com',
                    'username' => 'bacsilien',
                    'name' => 'Bác sĩ Nguyễn Thị Liên',
                    'address' => 'Quảng Nam - Việt Nam',
                    'phone' => '0971478563',
                ],
                [
                    'email' => 'bacsiquang@yopmail.com',
                    'username' => 'bacsiquang',
                    'name' => 'Bác sĩ Nguyễn Văn Quang',
                    'address' => 'Thủy Châu - Việt Nam',
                    'phone' => '0971456233',
                ],
                [
                    'email' => 'bacsithanhthanh@yopmail.com',
                    'username' => 'bacsithanhthanh',
                    'name' => 'Bác sĩ Nguyễn Thị Thanh Thanh',
                    'address' => 'Đà Nẵng - Việt Nam',
                    'phone' => '0971561233',
                ],

                [
                    'email' => 'bacsihuyhoang@yopmail.com',
                    'username' => 'bacsihuyhoang',
                    'name' => 'Bác sĩ Nguyễn Văn Huy Hoàng',
                    'address' => 'Hà Nội - Việt Nam',
                    'phone' => '0971156233',
                ],
                [
                    'email' => 'bacsiminhtuan@yopmail.com',
                    'username' => 'bacsiminhtuan',
                    'name' => 'Bác sĩ Nguyễn Minh Tuấn',
                    'address' => 'Huế - Việt Nam',
                    'phone' => '0975631233',
                ],
                [
                    'email' => 'bacsilinhnga@yopmail.com',
                    'username' => 'bacsilinhnga',
                    'name' => 'Bác sĩ Nguyễn Thị Linh Nga',
                    'address' => 'Hồ Chí Minh - Việt Nam',
                    'phone' => '0971289233',
                ],
                [
                    'email' => 'bacsiduyanh@yopmail.com',
                    'username' => 'bacsiduyanh',
                    'name' => 'Bác sĩ Nguyễn Duy Anh',
                    'address' => 'Hải Phòng - Việt Nam',
                    'phone' => '0956231233',
                ],
                [
                    'email' => 'bacsihamy@yopmail.com',
                    'username' => 'bacsihamy',
                    'name' => 'Bác sĩ Trần Thị Hà My',
                    'address' => 'Huế - Việt Nam',
                    'phone' => '0971561233',
                ],
                [
                    'email' => 'bacsimytam@yopmail.com',
                    'username' => 'bacsimytam',
                    'name' => 'Bác sĩ Phạm Thị Mỹ Tâm',
                    'address' => 'Đà Lạt - Việt Nam',
                    'phone' => '0971212633',
                ],
                [
                    'email' => 'bacsiquanghuy@yopmail.com',
                    'username' => 'bacsiquanghuy',
                    'name' => 'Bác sĩ Nguyễn Văn Quang Huy',
                    'address' => 'Bình Định - Việt Nam',
                    'phone' => '0971459233',
                ],
                [
                    'email' => 'bacsingoctrinh@yopmail.com',
                    'username' => 'bacsingoctrinh',
                    'name' => 'Bác sĩ Nguyễn Thị Ngọc Trinh',
                    'address' => 'Hải Dương - Việt Nam',
                    'phone' => '0974891233',
                ],
                [
                    'email' => 'bacsihuonggiang@yopmail.com',
                    'username' => 'bacsihuonggiang',
                    'name' => 'Bác sĩ Trương Ngọc Hương Giang',
                    'address' => 'Phú Đa - Phú Vang - TT Huế - Việt Nam',
                    'phone' => '09715621233',
                ],
            ];

            // lấy 3 bệnh viện đầu tiên
            $hospitals = User::where('role', 'hospital')->limit(3)->get();
            // foreach ($hospitals as $index => $hospital) {
            //     $hospital->idDepartments = HospitalDepartment::where('id_hospital', $hospital->id)->get()->pluck('id_department');
            // }

            $pathFolder = 'storage/app/public/image/avatars/doctors/';
            if (!File::exists($pathFolder)) {
                File::makeDirectory($pathFolder, 0755, true);
            }
            foreach ($doctors as $index => $doctor) {
                try {
                    while (true) {
                        $client = new Client;
                        $response = $client->get('https://picsum.photos/200/200');
                        $imageContent = $response->getBody()->getContents();
                        $nameImage = uniqid() . '.jpg';
                        $avatar = $pathFolder . $nameImage;
                        if (file_put_contents($avatar, $imageContent)) {
                            $data = array_merge(
                                $doctor,
                                [
                                    'role' => 'doctor',
                                    'password' => Hash::make('123456'),
                                    'avatar' => 'storage/image/avatars/doctors/' . $nameImage,
                                    'is_accept' => 1,
                                    'token_verify_email' => null,
                                    'email_verified_at' => now(),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]
                            );
                            $user = User::create($data);
                            // infor_hospital
                            // mảng doctors gồm 15 doctor => chia mảng làm 3 cho 3 bệnh viện
                            // mỗi bệnh viện 5 bác sĩ
                            $result = floor($index / 8);

                            $data = [
                                'id_doctor' => $user->id,
                                'id_hospital' => $hospitals[$result]->id,
                                // 'id_department' => $hospitals[$result]->idDepartments->random(),
                                'id_department' => floor(($index - $result * 8) / 2 + 1),
                                'is_confirm' => 1,
                                'province_code' => random_int(1, 63),
                                'date_of_birth' => date('Y-m-d', mt_rand(strtotime('2000-01-01'), strtotime('2002-12-29'))),
                                'experience' => random_int(1, 10),
                                'gender' => random_int(0, 2),
                                'search_number' => random_int(0, 300),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            $inforDoctor = InforDoctorRepository::createDoctor($data);
                            // infor_hospital
                            break;
                        }
                    }
                } catch (\Exception $e) {
                }
            }
        } catch (\Exception $e) {
        }
    }
}
