<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvincesSeeder extends Seeder
{
    public function run()
    {
        $provinces = [
            ['province_code' => '63', 'name' => 'Cà Mau'],
            ['province_code' => '62', 'name' => 'Bạc Liêu'],
            ['province_code' => '61', 'name' => 'Sóc Trăng'],
            ['province_code' => '60', 'name' => 'Trà Vinh'],
            ['province_code' => '59', 'name' => 'Hậu Giang'],
            ['province_code' => '58', 'name' => 'Kiên Giang'],
            ['province_code' => '57', 'name' => 'Vĩnh Long'],
            ['province_code' => '56', 'name' => 'An Giang'],
            ['province_code' => '55', 'name' => 'Bến Tre'],
            ['province_code' => '54', 'name' => 'Tiền Giang'],
            ['province_code' => '53', 'name' => 'Đồng Tháp'],
            ['province_code' => '52', 'name' => 'Long An'],
            ['province_code' => '51', 'name' => 'Bà Rịa–Vũng Tàu'],
            ['province_code' => '50', 'name' => 'Đồng Nai'],
            ['province_code' => '49', 'name' => 'Bình Dương'],
            ['province_code' => '48', 'name' => 'Tây Ninh'],
            ['province_code' => '47', 'name' => 'Bình Phước'],
            ['province_code' => '46', 'name' => 'Bình Thuận'],
            ['province_code' => '45', 'name' => 'Ninh Thuận'],
            ['province_code' => '44', 'name' => 'Lâm Đồng'],
            ['province_code' => '43', 'name' => 'Khánh Hòa'],
            ['province_code' => '42', 'name' => 'Đắk Nông'],
            ['province_code' => '41', 'name' => 'Đắk Lắk'],
            ['province_code' => '40', 'name' => 'Phú Yên'],
            ['province_code' => '39', 'name' => 'Bình Định'],
            ['province_code' => '38', 'name' => 'Gia Lai'],
            ['province_code' => '37', 'name' => 'Kon Tum'],
            ['province_code' => '36', 'name' => 'Quảng Ngãi'],
            ['province_code' => '35', 'name' => 'Quảng Nam'],
            ['province_code' => '34', 'name' => 'Thừa Thiên-Huế'],
            ['province_code' => '33', 'name' => 'Quảng Trị'],
            ['province_code' => '32', 'name' => 'Quảng Bình'],
            ['province_code' => '31', 'name' => 'Hà Tĩnh'],
            ['province_code' => '30', 'name' => 'Nghệ An'],
            ['province_code' => '29', 'name' => 'Thanh Hóa'],
            ['province_code' => '28', 'name' => 'Ninh Bình'],
            ['province_code' => '27', 'name' => 'Nam Định'],
            ['province_code' => '26', 'name' => 'Hà Nam'],
            ['province_code' => '25', 'name' => 'Thái Bình'],
            ['province_code' => '24', 'name' => 'Hải Dương'],
            ['province_code' => '23', 'name' => 'Hưng Yên'],
            ['province_code' => '22', 'name' => 'Hòa Bình'],
            ['province_code' => '21', 'name' => 'Quảng Ninh'],
            ['province_code' => '20', 'name' => 'Bắc Giang'],
            ['province_code' => '19', 'name' => 'Bắc Ninh'],
            ['province_code' => '18', 'name' => 'Vĩnh Phúc'],
            ['province_code' => '17', 'name' => 'Phú Thọ'],
            ['province_code' => '16', 'name' => 'Sơn La'],
            ['province_code' => '15', 'name' => 'Thái Nguyên'],
            ['province_code' => '14', 'name' => 'Bắc Kạn'],
            ['province_code' => '13', 'name' => 'Tuyên Quang'],
            ['province_code' => '12', 'name' => 'Yên Bái'],
            ['province_code' => '11', 'name' => 'Lạng Sơn'],
            ['province_code' => '10', 'name' => 'Cao Bằng'],
            ['province_code' => '9', 'name' => 'Hà Giang'],
            ['province_code' => '8', 'name' => 'Lào Cai'],
            ['province_code' => '7', 'name' => 'Lai Châu'],
            ['province_code' => '6', 'name' => 'Điện Biên'],
            ['province_code' => '5', 'name' => 'Cần Thơ'],
            ['province_code' => '4', 'name' => 'Đà Nẵng'],
            ['province_code' => '3', 'name' => 'Hải Phòng'],
            ['province_code' => '2', 'name' => 'Hồ Chí Minh City'],
            ['province_code' => '1', 'name' => 'Hà Nội'],
        ];

        usort($provinces, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        foreach ($provinces as $province) {
            Province::create([
                'province_code' => $province['province_code'],
                'name' => $province['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
