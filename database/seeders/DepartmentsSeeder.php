<?php

namespace Database\Seeders;

use App\Models\Department;
use Database\Factories\FakeImageFactory;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DepartmentsSeeder extends Seeder
{
    // public function run()
    // {
    //     $departments = [
    //         [
    //             'name' => 'Đa Khoa',
    //             'description' => 'Khoa Đa Khoa chuyên cung cấp chăm sóc y tế tổng quát cho các bệnh nhân.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Nha Khoa',
    //             'description' => 'Khoa Nha Khoa tập trung vào chăm sóc và điều trị về vấn đề răng miệng và nha khoa.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Da Liễu',
    //             'description' => 'Khoa Da Liễu chuyên chăm sóc và điều trị các vấn đề liên quan đến da.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Nhãn Khoa',
    //             'description' => 'Khoa Nhãn Khoa chuyên chẩn đoán và điều trị các bệnh liên quan đến mắt.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Sản - Phụ Khoa',
    //             'description' => 'Khoa Sản - Phụ Khoa chăm sóc phụ nữ mang thai và các vấn đề phụ khoa.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Trị Liệu Thần Kinh Cột Sống',
    //             'description' => 'Khoa Trị Liệu Thần Kinh Cột Sống chuyên trị liệu các vấn đề về thần kinh và cột sống.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Nhi Khoa',
    //             'description' => 'Khoa Nhi Khoa chăm sóc sức khỏe trẻ em và điều trị các bệnh trẻ em.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Tai - Mũi - Họng',
    //             'description' => 'Khoa Tai - Mũi - Họng chuyên chẩn đoán và điều trị các vấn đề về tai, mũi, họng.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Tiêu Hóa',
    //             'description' => 'Khoa Tiêu Hóa chăm sóc và điều trị các vấn đề về tiêu hóa và dạ dày.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Tâm Lý',
    //             'description' => 'Khoa Tâm Lý cung cấp dịch vụ tư vấn tâm lý và chăm sóc tâm lý.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Vật Lý Trị Liệu',
    //             'description' => 'Khoa Vật Lý Trị Liệu chuyên cung cấp phục hồi chức năng và trị liệu vật lý.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Tâm Thần',
    //             'description' => 'Khoa Tâm Thần tập trung vào chăm sóc và điều trị các vấn đề tâm thần.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Trị Liệu Hô Hấp',
    //             'description' => 'Khoa Trị Liệu Hô Hấp chuyên trị liệu các bệnh về hô hấp.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Y Học Giấc Ngủ',
    //             'description' => 'Khoa Y Học Giấc Ngủ nghiên cứu và điều trị các vấn đề liên quan đến giấc ngủ.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Nam Khoa',
    //             'description' => 'Khoa Nam Khoa chăm sóc và điều trị các vấn đề liên quan đến nam giới.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Chẩn Đoán Hình Ảnh',
    //             'description' => 'Khoa Chẩn Đoán Hình Ảnh chuyên chẩn đoán bằng cách sử dụng hình ảnh.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Dinh Dưỡng và Chế Độ Ăn Uống',
    //             'description' => 'Khoa Dinh Dưỡng và Chế Độ Ăn Uống tư vấn về dinh dưỡng và chế độ ăn uống.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Nội Tổng Quát',
    //             'description' => 'Khoa Nội Tổng Quát chăm sóc tổng quát và điều trị các bệnh nội tiết.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Ngoại Thần Kinh',
    //             'description' => 'Khoa Ngoại Thần Kinh chăm sóc và điều trị các vấn đề về ngoại thần kinh.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Chỉnh Hình',
    //             'description' => 'Khoa Chỉnh Hình chuyên điều trị và phục hình các vấn đề xương và cơ.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Ung Thư - Ung Bướu',
    //             'description' => 'Khoa Ung Thư - Ung Bướu chăm sóc và điều trị các bệnh liên quan đến ung thư và ung bướu.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Phổi',
    //             'description' => 'Khoa Phổi chăm sóc và điều trị các vấn đề về hệ hô hấp.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Tiết Niệu',
    //             'description' => 'Khoa Tiết Niệu chăm sóc và điều trị các vấn đề về hệ tiết niệu.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Thận',
    //             'description' => 'Khoa Thận chăm sóc và điều trị các vấn đề về thận.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Bệnh Truyền Nhiễm',
    //             'description' => 'Khoa Bệnh Truyền Nhiễm chăm sóc và điều trị các bệnh truyền nhiễm.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Gan Mật',
    //             'description' => 'Khoa Gan Mật chăm sóc và điều trị các vấn đề liên quan đến gan và mật.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Chuyên Khoa Chân',
    //             'description' => 'Khoa Chuyên Khoa Chân chăm sóc và điều trị các vấn đề về chân.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Y Học Cổ Truyền',
    //             'description' => 'Khoa Y Học Cổ Truyền sử dụng phương pháp cổ truyền trong điều trị.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Chuyên Khoa Béo Phì',
    //             'description' => 'Khoa Chuyên Khoa Béo Phì tập trung vào điều trị bệnh béo phì.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Phẫu Thuật Thẩm Mỹ',
    //             'description' => 'Khoa Phẫu Thuật Thẩm Mỹ chuyên thực hiện các ca phẫu thuật thẩm mỹ.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Bệnh Lý Học',
    //             'description' => 'Khoa Bệnh Lý Học chẩn đoán và điều trị các bệnh lý.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Học Thể Thao',
    //             'description' => 'Khoa Khoa Học Thể Thao nghiên cứu và điều trị các vấn đề về thể thao.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Tham Vấn Tâm Lý',
    //             'description' => 'Khoa Tham Vấn Tâm Lý tư vấn và hỗ trợ tâm lý.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khúc Xạ Nhãn Khoa',
    //             'description' => 'Khoa Khúc Xạ Nhãn Khoa sử dụng khúc xạ trong chẩn đoán và điều trị.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Thần Kinh',
    //             'description' => 'Khoa Thần Kinh chăm sóc và điều trị các vấn đề về thần kinh.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Huyết Học',
    //             'description' => 'Khoa Huyết Học chẩn đoán và điều trị các vấn đề về huyết học.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Thấp Khớp',
    //             'description' => 'Khoa Khoa Thấp Khớp chăm sóc và điều trị các vấn đề về các khớp thấp.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Khoa Nội Tiết',
    //             'description' => 'Khoa Khoa Nội Tiết chăm sóc và điều trị các vấn đề về nội tiết.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Tim Mạch',
    //             'description' => 'Khoa Tim Mạch chăm sóc và điều trị các vấn đề về tim mạch.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Y Học Phục Hồi Chức Năng',
    //             'description' => 'Khoa Y Học Phục Hồi Chức Năng tập trung vào phục hồi chức năng của cơ thể.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Tình Dục Học',
    //             'description' => 'Khoa Tình Dục Học tập trung vào sức khỏe tình dục và giới tính.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Lão Khoa',
    //             'description' => 'Khoa Lão Khoa chăm sóc và điều trị các vấn đề liên quan đến người cao tuổi.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Miễn Dịch Học',
    //             'description' => 'Khoa Miễn Dịch Học chăm sóc và nghiên cứu về hệ miễn dịch của cơ thể.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Cơ - Xương - Khớp',
    //             'description' => 'Khoa Cơ - Xương - Khớp chăm sóc và điều trị các vấn đề về cơ, xương, và khớp.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'name' => 'Ngoại Tổng Quát',
    //             'description' => 'Khoa Ngoại Tổng Quát chăm sóc tổng quát và điều trị các vấn đề ngoại tiết.',
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ];

    //     // Thêm dữ liệu vào bảng "departments"
    //     DB::table('departments')->insert($departments);
    // }

    public function run()
    {
        $pathFolder = 'public/storage/image/thumbnail/departments';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $departments = [
            [
                'name' => 'Đa Khoa',
                'description' => 'Khoa Đa Khoa chuyên cung cấp chăm sóc y tế tổng quát cho các bệnh nhân.',
            ],
            [
                'name' => 'Nha Khoa',
                'description' => 'Khoa Nha Khoa tập trung vào chăm sóc và điều trị về vấn đề răng miệng và nha khoa.',
            ],
            [
                'name' => 'Da Liễu',
                'description' => 'Khoa Da Liễu chuyên chăm sóc và điều trị các vấn đề liên quan đến da.',
            ],
            [
                'name' => 'Nhãn Khoa',
                'description' => 'Khoa Nhãn Khoa chuyên chẩn đoán và điều trị các bệnh liên quan đến mắt.',
            ],
            [
                'name' => 'Sản - Phụ Khoa',
                'description' => 'Khoa Sản - Phụ Khoa chăm sóc phụ nữ mang thai và các vấn đề phụ khoa.',
            ],
            [
                'name' => 'Trị Liệu Thần Kinh Cột Sống',
                'description' => 'Khoa Trị Liệu Thần Kinh Cột Sống chuyên trị liệu các vấn đề về thần kinh và cột sống.',
            ],
            [
                'name' => 'Nhi Khoa',
                'description' => 'Khoa Nhi Khoa chăm sóc sức khỏe trẻ em và điều trị các bệnh trẻ em.',
            ],
            [
                'name' => 'Tai - Mũi - Họng',
                'description' => 'Khoa Tai - Mũi - Họng chuyên chẩn đoán và điều trị các vấn đề về tai, mũi, họng.',
            ],
            [
                'name' => 'Khoa Tiêu Hóa',
                'description' => 'Khoa Tiêu Hóa chăm sóc và điều trị các vấn đề về tiêu hóa và dạ dày.',
            ],
            [
                'name' => 'Tâm Lý',
                'description' => 'Khoa Tâm Lý cung cấp dịch vụ tư vấn tâm lý và chăm sóc tâm lý.',
            ],
            [
                'name' => 'Vật Lý Trị Liệu',
                'description' => 'Khoa Vật Lý Trị Liệu chuyên cung cấp phục hồi chức năng và trị liệu vật lý.',
            ],
            [
                'name' => 'Tâm Thần',
                'description' => 'Khoa Tâm Thần tập trung vào chăm sóc và điều trị các vấn đề tâm thần.',
            ],
            [
                'name' => 'Trị Liệu Hô Hấp',
                'description' => 'Khoa Trị Liệu Hô Hấp chuyên trị liệu các bệnh về hô hấp.',
            ],
            [
                'name' => 'Y Học Giấc Ngủ',
                'description' => 'Khoa Y Học Giấc Ngủ nghiên cứu và điều trị các vấn đề liên quan đến giấc ngủ.',
            ],
            [
                'name' => 'Nam Khoa',
                'description' => 'Khoa Nam Khoa chăm sóc và điều trị các vấn đề liên quan đến nam giới.',
            ],
            [
                'name' => 'Chẩn Đoán Hình Ảnh',
                'description' => 'Khoa Chẩn Đoán Hình Ảnh chuyên chẩn đoán bằng cách sử dụng hình ảnh.',
            ],
            [
                'name' => 'Dinh Dưỡng và Chế Độ Ăn Uống',
                'description' => 'Khoa Dinh Dưỡng và Chế Độ Ăn Uống tư vấn về dinh dưỡng và chế độ ăn uống.',
            ],
            [
                'name' => 'Nội Tổng Quát',
                'description' => 'Khoa Nội Tổng Quát chăm sóc tổng quát và điều trị các bệnh nội tiết.',
            ],
            [
                'name' => 'Ngoại Thần Kinh',
                'description' => 'Khoa Ngoại Thần Kinh chăm sóc và điều trị các vấn đề về ngoại thần kinh.',
            ],
            [
                'name' => 'Chỉnh Hình',
                'description' => 'Khoa Chỉnh Hình chuyên điều trị và phục hình các vấn đề xương và cơ.',
            ],
            [
                'name' => 'Ung Thư - Ung Bướu',
                'description' => 'Khoa Ung Thư - Ung Bướu chăm sóc và điều trị các bệnh liên quan đến ung thư và ung bướu.',
            ],
            [
                'name' => 'Khoa Phổi',
                'description' => 'Khoa Phổi chăm sóc và điều trị các vấn đề về hệ hô hấp.',
            ],
            [
                'name' => 'Khoa Tiết Niệu',
                'description' => 'Khoa Tiết Niệu chăm sóc và điều trị các vấn đề về hệ tiết niệu.',
            ],
            [
                'name' => 'Khoa Thận',
                'description' => 'Khoa Thận chăm sóc và điều trị các vấn đề về thận.',
            ],
            [
                'name' => 'Bệnh Truyền Nhiễm',
                'description' => 'Khoa Bệnh Truyền Nhiễm chăm sóc và điều trị các bệnh truyền nhiễm.',
            ],
            [
                'name' => 'Gan Mật',
                'description' => 'Khoa Gan Mật chăm sóc và điều trị các vấn đề liên quan đến gan và mật.',
            ],
            [
                'name' => 'Chuyên Khoa Chân',
                'description' => 'Khoa Chuyên Khoa Chân chăm sóc và điều trị các vấn đề về chân.',
            ],
            [
                'name' => 'Y Học Cổ Truyền',
                'description' => 'Khoa Y Học Cổ Truyền sử dụng phương pháp cổ truyền trong điều trị.',
            ],
            [
                'name' => 'Chuyên Khoa Béo Phì',
                'description' => 'Khoa Chuyên Khoa Béo Phì tập trung vào điều trị bệnh béo phì.',
            ],
            [
                'name' => 'Phẫu Thuật Thẩm Mỹ',
                'description' => 'Khoa Phẫu Thuật Thẩm Mỹ chuyên thực hiện các ca phẫu thuật thẩm mỹ.',
            ],
            [
                'name' => 'Bệnh Lý Học',
                'description' => 'Khoa Bệnh Lý Học chẩn đoán và điều trị các bệnh lý.',
            ],
            [
                'name' => 'Khoa Học Thể Thao',
                'description' => 'Khoa Khoa Học Thể Thao nghiên cứu và điều trị các vấn đề về thể thao.',
            ],
            [
                'name' => 'Tham Vấn Tâm Lý',
                'description' => 'Khoa Tham Vấn Tâm Lý tư vấn và hỗ trợ tâm lý.',
            ],
            [
                'name' => 'Khúc Xạ Nhãn Khoa',
                'description' => 'Khoa Khúc Xạ Nhãn Khoa sử dụng khúc xạ trong chẩn đoán và điều trị.',
            ],
            [
                'name' => 'Thần Kinh',
                'description' => 'Khoa Thần Kinh chăm sóc và điều trị các vấn đề về thần kinh.',
            ],
            [
                'name' => 'Huyết Học',
                'description' => 'Khoa Huyết Học chẩn đoán và điều trị các vấn đề về huyết học.',
            ],
            [
                'name' => 'Khoa Thấp Khớp',
                'description' => 'Khoa Khoa Thấp Khớp chăm sóc và điều trị các vấn đề về các khớp thấp.',
            ],
            [
                'name' => 'Khoa Nội Tiết',
                'description' => 'Khoa Khoa Nội Tiết chăm sóc và điều trị các vấn đề về nội tiết.',
            ],
            [
                'name' => 'Tim Mạch',
                'description' => 'Khoa Tim Mạch chăm sóc và điều trị các vấn đề về tim mạch.',
            ],
            [
                'name' => 'Y Học Phục Hồi Chức Năng',
                'description' => 'Khoa Y Học Phục Hồi Chức Năng tập trung vào phục hồi chức năng của cơ thể.',
            ],
            [
                'name' => 'Tình Dục Học',
                'description' => 'Khoa Tình Dục Học tập trung vào sức khỏe tình dục và giới tính.',
            ],
            [
                'name' => 'Lão Khoa',
                'description' => 'Khoa Lão Khoa chăm sóc và điều trị các vấn đề liên quan đến người cao tuổi.',
            ],
            [
                'name' => 'Miễn Dịch Học',
                'description' => 'Khoa Miễn Dịch Học chăm sóc và nghiên cứu về hệ miễn dịch của cơ thể.',
            ],
            [
                'name' => 'Cơ - Xương - Khớp',
                'description' => 'Khoa Cơ - Xương - Khớp chăm sóc và điều trị các vấn đề về cơ, xương, và khớp.',
            ],
            [
                'name' => 'Ngoại Tổng Quát',
                'description' => 'Khoa Ngoại Tổng Quát chăm sóc tổng quát và điều trị các vấn đề ngoại tiết.',
            ],
        ];

        // Cách 1 : Chậm
        // $thumbnails = [];

        // foreach ($departments as $index => $department) {
        //     $thumbnail = FakeImageFactory::new()->createThumbnailDepartment();
        //     while (!$thumbnail) {
        //         $thumbnail = FakeImageFactory::new()->createThumbnailDepartment();
        //     }
        //     $thumbnails[$index] = 'storage/image/thumbnail/departments/' . $thumbnail;
        // }

        // foreach ($departments as $index => $department) {
        //     Department::create([
        //         'name' => $department['name'],
        //         'description' => $department['description'],
        //         'thumbnail' => $thumbnails[$index],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Cách 2
        foreach ($departments as $index => $department) {
            try {
                $pathFolder = 'storage/app/public/image/thumbnail/departments/';
                if (!File::exists($pathFolder)) {
                    File::makeDirectory($pathFolder, 0755, true);
                }
                $client = new Client;
                while (true) {
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $thumbnail = $pathFolder . $nameImage;
                    if (file_put_contents($thumbnail, $imageContent)) {
                        Department::create([
                            'name' => $department['name'],
                            'description' => $department['description'],
                            'thumbnail' => 'storage/image/thumbnail/departments/' . $nameImage,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        break;
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }
}
