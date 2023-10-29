<?php

namespace Database\Seeders;

use App\Models\HealthInsurance;
use Illuminate\Database\Seeder;

class HealthInsurancesSeeder extends Seeder
{
    public function run()
    {
        try {
            $healthInsurances = [
                [
                    'name' => 'Bảo hiểm Y tế cơ bản',
                    'description' => '<ol><li><strong style="color: var(--tw-prose-bold);">Tên</strong><strong>: Bảo hiểm Y tế cơ bản</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực</strong><strong>: Thường xuyên, hàng năm</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán %</strong><strong>: Thường từ 80% đến 100% tùy thuộc vào mức độ phủ sóng</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán cho những chuyên khoa, bệnh tật nào</strong><strong>: Phụ thuộc vào hợp đồng, bao gồm mọi loại bệnh tật</strong></li><li><strong style="color: var(--tw-prose-bold);">Do ai phát hành</strong><strong>: Chính phủ Việt Nam thông qua Bảo hiểm Xã hội (BHXH)</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực từ ngày nào đến ngày nào</strong><strong>: Thường từ ngày 1/1 đến ngày 31/12 của mỗi năm</strong></li></ol>',
                ],
                [
                    'name' => 'Bảo hiểm Y tế tự nguyện',
                    'description' => '<ol><li><strong style="color: var(--tw-prose-bold);">Tên</strong><strong>: Bảo hiểm Y tế tự nguyện</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực</strong><strong>: Thường là hợp đồng dài hạn, có thể mua hàng năm hoặc dài hạn</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán %</strong><strong>: Tùy thuộc vào hợp đồng và mức độ bảo hiểm chọn</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán cho những chuyên khoa, bệnh tật nào</strong><strong>: Phụ thuộc vào hợp đồng, bao gồm mọi loại bệnh tật</strong></li><li><strong style="color: var(--tw-prose-bold);">Do ai phát hành</strong><strong>: Các công ty bảo hiểm như Bảo Việt, Prudential, Manulife, vv.</strong></li></ol><p><br></p>',
                ],
                [
                    'name' => 'Bảo hiểm sức khỏe toàn diện',
                    'description' => '<ol><li><strong style="color: var(--tw-prose-bold);">Tên</strong><strong>: Bảo hiểm sức khỏe toàn diện</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực</strong><strong>: Thường là hợp đồng dài hạn</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán %</strong><strong>: Bao gồm cả dịch vụ y tế thẩm mỹ, thường từ 80% đến 100% tùy thuộc vào mức độ phủ sóng</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán cho những chuyên khoa, bệnh tật nào</strong><strong>: Bao gồm cả dịch vụ thẩm mỹ và mọi loại bệnh tật</strong></li><li><strong style="color: var(--tw-prose-bold);">Do ai phát hành</strong><strong>: Các công ty bảo hiểm như Bảo Việt, AIA, Liberty, vv.</strong></li></ol><p><br></p>',
                ],
                [
                    'name' => 'Bảo hiểm Tai nạn và Bệnh nghề nghiệp',
                    'description' => '<ol><li><strong style="color: var(--tw-prose-bold);">Tên</strong><strong>: Bảo hiểm Tai nạn và Bệnh nghề nghiệp</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực</strong><strong>: Thường là hợp đồng dài hạn</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán %</strong><strong>: Bồi thường cho tai nạn và bệnh nghề nghiệp, thường 100%</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán cho những chuyên khoa, bệnh tật nào</strong><strong>: Bao gồm cả tai nạn và bệnh nghề nghiệp</strong></li><li><strong style="color: var(--tw-prose-bold);">Do ai phát hành</strong><strong>: Các công ty bảo hiểm như Bảo Việt, PVI, Bảo Minh, vv.</strong></li></ol><p><br></p>',
                ],
                [
                    'name' => 'Bảo hiểm Phẫu thuật và Thẩm mỹ',
                    'description' => '<ol><li><strong style="color: var(--tw-prose-bold);">Tên</strong><strong>: Bảo hiểm Phẫu thuật và Thẩm mỹ</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực</strong><strong>: Thường là hợp đồng dài hạn</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán %</strong><strong>: Tùy thuộc vào hợp đồng và mức độ bảo hiểm chọn, có thể bao gồm cả phẫu thuật thẩm mỹ</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán cho những chuyên khoa, bệnh tật nào</strong><strong>: Bao gồm cả phẫu thuật thẩm mỹ và các phẫu thuật khác</strong></li><li><strong style="color: var(--tw-prose-bold);">Do ai phát hành</strong><strong>: Các công ty bảo hiểm như Bảo Việt, PVI, Bảo Minh, vv.</strong></li></ol><p><br></p>',
                ],
                [
                    'name' => 'Bảo hiểm Dịch vụ y tế quốc tế',
                    'description' => '<ol><li><strong style="color: var(--tw-prose-bold);">Tên</strong><strong>: Bảo hiểm Dịch vụ y tế quốc tế</strong></li><li><strong style="color: var(--tw-prose-bold);">Hiệu lực</strong><strong>: Thường là hợp đồng dài hạn</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán %</strong><strong>: Tùy thuộc vào hợp đồng và mức độ bảo hiểm chọn, bao gồm cả dịch vụ y tế tại nước ngoài</strong></li><li><strong style="color: var(--tw-prose-bold);">Thanh toán cho những chuyên khoa, bệnh tật nào</strong><strong>: Bao gồm cả dịch vụ y tế thẩm mỹ và mọi loại bệnh tật</strong></li><li><strong style="color: var(--tw-prose-bold);">Do ai phát hành</strong><strong>: Các công ty bảo hiểm như Bảo Việt, AIA, Liberty, vv.</strong></li></ol><p><br></p>',
                ],
            ];

            foreach ($healthInsurances as $index => $healthInsurance) {
                $data = array_merge(
                    $healthInsurance,
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                HealthInsurance::create($data);
            }
        } catch (\Exception $e) {
        }
    }
}
