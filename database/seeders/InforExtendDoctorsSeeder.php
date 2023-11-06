<?php

namespace Database\Seeders;

use App\Models\InforDoctor;
use App\Models\InforExtendDoctor;
use Illuminate\Database\Seeder;

class InforExtendDoctorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prominent = [
            [
                'title' => 'Hơn 13 năm kinh nghiệm về lĩnh vực Nhi khoa',
                'subtitle' => ['Bác sĩ có hơn 13 năm kinh nghiệm thăm khám, tư vấn điều trị bệnh lý Nhi khoa'],
            ],
            [
                'title' => 'Nền tảng chuyên môn vững chăc',
                'subtitle' => ['Bác sĩ là tác giả của nhiều công trình nghiên cứu và bài báo về các lĩnh vực Dinh dưỡng Nhi, Thận - tiết niệu Nhi, Tiêu hóa Nhi,...'],
            ],
            [
                'title' => 'Nhận tư vấn từ xa (Telemedicine)',
                'subtitle' => ['Bác sĩ có nhận thăm khám và tư vấn bệnh lý từ xa theo hình thức Telemedicine.'],
            ],
            [
                'title' => 'Chuyên điều trị các vấn đề Nhi khoa',
                'subtitle' => ['Bác sĩ có chuyên môn cao với các lĩnh vực chuyên môn Nhi tổng quát, thần kinh Nhi.'],
            ],
            [
                'title' => 'Kinh nghiệm lâu năm',
                'subtitle' => ['10 năm kinh nghiệm làm việc tại bệnh viện Nhi Đồng 2.'],
            ],
        ];

        $informationsArr = [
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Bác sĩ là một trong những chuyên gia đầu ngành về lĩnh vực Dinh dưỡng Nhi, Thận - Máu - Nội tiết, Hô hấp, Tiêu hóa Nhi tại Hải Phòng. Sở hữu nền tảng chuyên môn vững chắc, bác sĩ tốt nghiệp bằng Nhi khoa tại trường Đại học Sydney - Bệnh viện Westmead (Úc) năm 2012, được đào tạo và cấp Chứng chỉ Dinh dưỡng Nhi khoa - Đại học Y khoa Boston, Massachusetts, USA năm 2020.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Bên cạnh hoạt động thăm khám chữa bệnh, bác sĩ thường xuyên tham gia các chương trình tư vấn sức khỏe trên Đài Phát thanh và Truyền hình Hải Phòng, tham gia hoạt động tham vấn bài viết y khoa trên nền tảng Hello Bacsi.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Ngoài ra, bác sĩ cũng là tác giả của Công trình Nghiên cứu cấp Thành phố Hải Phòng năm 2018 về điều trị Hội chứng Thận hư kháng thuốc ở trẻ em, là tác giả của nhiều Công trình nghiên cứu và bài báo về lĩnh vực Dinh dưỡng Nhi, Thận - tiết niệu Nhi, Tiêu hóa Nhi.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Về mặt chuyên môn, bác sĩ Hạnh chuyên thăm khám và điều trị các bệnh lý Nhi tổng quát và thần kinh Nhi như: Động kinh, đau đầu, rối loạn giấc ngủ,...</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Bên cạnh hoạt động khám chữa bệnh, bác sĩ còn tham gia thực hiện nhiều báo cáo tại các hội nghị, tham gia nhiều nghiên cứu khoa học, viết báo về chuyên mục sức khỏe (hợp tác cùng báo EVA).</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Với nền tảng chuyên môn được đào tạo bài bản, bác sĩ tốt nghiệp Bác sĩ Nội Trú chuyên ngành Nhi khoa tại trường Đại học Y Dược TP.HCM. Bên cạnh hoạt động khám chữa bệnh, bác sĩ còn là giảng viên, tham gia công tác đào tạo và đảm nhận vai trò Phó trưởng bộ môn Nhi khoa, khoa Y, tại Đại học Duy Tân.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Ngoài ra, bác sĩ còn có mong muốn tư vấn miễn phí cho bệnh nhi có hoàn cảnh khó khăn. Vì thế, quý phụ huynh cần thăm khám, tư vấn bệnh lý cho con em nhưng đang ở trong tình thế khó hoặc biết những hoàn cảnh khác khó khăn có thể tìm đến bác sĩ.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Với hơn 15 năm kinh nghiệm, bên cạnh các dịch vụ thăm khám Sản - Phụ khoa, bác sĩ còn chuyên về các kỹ thuật phẫu thuật nội soi, trẻ hóa da và trẻ hóa âm đạo.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Sở hữu nền tảng chuyên môn vững chắc, bác sĩ cùng chuyên khoa tại Trung tâm Bernard luôn cập nhật các kỹ thuật hiện đại hỗ trợ tầm soát và chẩn đoán sớm các tình trạng bệnh lý phụ khoa, tầm soát ung thư.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Bên cạnh đó, với đặc thù về lĩnh vực Phụ khoa, bác sĩ luôn quan tâm đến trải nghiệm thăm khám của bệnh nhân, sẵn sàng tư vấn trao đổi giúp bệnh nhân gỡ bỏ được phần nào rào cản với các tình trạng bệnh lý nhằm có được phác đồ điều trị hiệu quả.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Hiện tại, Bác sĩ đang công tác chính tại Bệnh viện Từ Dũ, đổng thời hỗ trợ sản phụ sinh nở tại các bệnh viện như Vinmec, AIH, Phụ sản quốc tế Sài Gòn, Nguyễn Tri Phương, An Đông 7A,...</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Với nền tảng chuyên môn vững chắc, bác sĩ chuyên tư vấn và điều trị các vấn đề bệnh lý về tai, thính học nghe kém đến điếc nặng, sâu. Bên cạnh đó, bác sĩ còn tham gia phẫu thuật điều trị với các kỹ thuật như phẫu thuật tai - xương chủm, phẫu thuật nội soi mũi xoang, phẫu thuật họng, thanh quản và vùng cổ.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Trải qua quá trình công tác, bác sĩ từng giữ nhiều vị trí quan trọng như Phó Trưởng khoa Nội trú, Trưởng khoa Tai - Thính học tại Bệnh viện Tai Mũi Họng Sài Gòn.</span><br />',
            '<span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Bác sĩ tốt nghiệp trường Đại học Y Dược Huế, hoàn thành bậc Bác sĩ Chyên khoa II về Tai - Mũi - Họng và từng có khoảng thời gian tu nghiệp tại Viện Tai House, Los Angeles - Hoa Kỳ.</span><br />',
        ];

        $strengthsArr = [
            'Khám Tai - Mũi - Họng',
            'Khám và tư vấn Hen',
            'Khám và tư vấn béo phì cho trẻ',
            'Khám và tư vấn suy dinh dưỡng ở trẻ',
            'Khám Nhi thần kinh',
            'Tư vấn bệnh lý Nhi khoa',
            'Tư vấn dinh dưỡng Nhi',
            'Bệnh về Thận - Máu - Nội tiết ở trẻ',
            'Hồi sức cấp cứu',
            'Bệnh lý hô hấp',
            'Bệnh lý tiêu hóa',
            'Bệnh lý Da liễu',
            'Trẻ hóa da',
            'Trẻ hóa âm đạo',
            'Bệnh lý về tai',
            'Phẫu thuật tai - xương chủm',
            'Chỉnh hình chuỗi xương con',
            'Phẫu thuật Nội soi mũi xoang',
            'Phẫu thuật Họng, Thanh quản và vùng cổ',
        ];

        $workplaceArr = [
            'Đại học Y Dược Hải Phòng',
            'Bệnh viện Trẻ em Hải Phòng',
            'Bệnh viện Nhi Đồng 2',
            'Bệnh viện Nhi đồng 2 TP. Hồ Chí Minh',
            'Đại học Duy Tân',
            'Phòng khám đa khoa Pasteur, Đà Nẵng',
            'Trung tâm Y khoa Chuyên sâu Quốc tế Bernard Healthcare',
            'Bệnh viện Từ Dũ',
            'Bệnh viện Tai Mũi Họng Sài Gòn',
            'Bệnh viện Đa khoa Lâm Đồng',
            'Bệnh viện Đa khoa Hà Nội',
            'Bệnh viện Bạch Mai',
            'Bệnh viện Chợ Rẫy',
            'Bệnh viện Bình Dân, TP. Hồ Chí Minh',
            'Bệnh viện Nhi Đồng 1, TP. Hồ Chí Minh',
            'Bệnh viện Đa khoa Tỉnh Bắc Ninh',
            'Bệnh viện Đa khoa Tỉnh Thanh Hóa',
            'Bệnh viện Đa khoa Tỉnh Đồng Nai',
            'Bệnh viện Đa khoa Tỉnh Cần Thơ',
            'Bệnh viện Đa khoa Tỉnh Khánh Hòa',
            'Bệnh viện Đa khoa Tỉnh Đà Nẵng',
            'Bệnh viện Đa khoa Tỉnh Hải Phòng',
        ];

        $positionArr = [
            'Giảng viên bộ môn Nhi khoa',
            'Bác sĩ khoa Thận - máu - nội tiết',
            'Bác sĩ chuyên khoa Nhi',
            'Bác sĩ khoa Hồi sức tích cực & Chống độc',
            'Phó trưởng bộ môn Nhi, Khoa Y',
            'Quản lý',
            'Bác sĩ chuyên khoa Sản phụ khoa',
            'Bác sĩ chuyên khoa Sản - Phụ khoa',
            'Trưởng khoa Tai – Thính học',
            'Phó trưởng khoa Nội trú',
            'Bác sĩ điều trị Tai Mũi Họng',
            'Bác sĩ chuyên khoa Ngoại tiêu hóa',
            'Phó trưởng bộ môn Sản khoa',
            'Bác sĩ chuyên khoa Thận - máu - nội tiết',
            'Bác sĩ chuyên khoa Nhiễm khuẩn',
            'Bác sĩ chuyên khoa Nội tiết',
            'Phó trưởng bộ môn Tai Mũi Họng',
            'Trưởng khoa Nhi - Sản',
            'Phó trưởng bộ môn Sản khoa',
            'Bác sĩ chuyên khoa Ngoại trú',
            'Trưởng khoa Da liễu',
            'Phó trưởng bộ môn Tim mạch',
            'Bác sĩ chuyên khoa Nha khoa',
            'Bác sĩ chuyên khoa Mắt',
            'Trưởng khoa Tai - Mũi - Họng',
            'Phó trưởng bộ môn Nha khoa',
        ];

        $locationArr = [
            'Trường Đại học Y Hà Nội - Hanoi Medical University (HMU)',
            'Trường Đại học Y Dược Thành phố Hồ Chí Minh - University of Medicine and Pharmacy at Ho Chi Minh City (UMP)',
            'Trường Đại học Y Dược Huế - Hue University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Hải Phòng - Hai Phong University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Cần Thơ - Can Tho University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Đà Nẵng - University of Medicine and Pharmacy at Da Nang',
            'Trường Đại học Y Dược Hà Tĩnh - Ha Tinh University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Phạm Ngọc Thạch - Pham Ngoc Thach University of Medicine',
            'Trường Đại học Y Dược Quốc gia Hà Nội - Hanoi National University of Education',
            'Trường Đại học Y Dược Nha Trang - Nha Trang University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Vinh - Vinh University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Hòa Bình - Hoa Binh University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Yên Bái - Yen Bai University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Quy Nhơn - Quy Nhon University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Hà Giang - Ha Giang University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Bắc Giang - Bac Giang University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Bắc Kạn - Bac Kan University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Lạng Sơn - Lang Son University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Thanh Hóa - Thanh Hoa University of Medicine and Pharmacy',
            'Trường Đại học Y Dược Hòa Lạc - Hoa Lac University of Medicine and Pharmacy',
        ];

        $researchWorkArr = [
            'Nghiên cứu vi khuẩn ái khí trong các thể viêm amidan tại Huế.',
            'Nghiên cứu phẫu thuật vách ngăn nội soi bằng đường rạch chữ T tại Lâm Đồng.',
            'Thay thế xương bàn đạp trong điều trị xốp xơ tai tại Bệnh viện Tai Mũi Họng Sài Gòn.',
            'Nghiên cứu cấy điện cực ốc tai tại Bệnh viện Tai Mũi Họng Sài Gòn.',
            'Nghiên cứu khâu xuyên sau phẫu thuật vách ngăn nội soi không cần nhét bấc.',
            'Chỉnh hình xương con tự thân 1 thì/bệnh cholesteatome bẩm sinh người lớn.',
        ];

        $awardsRecognitionArr = [
            'Tác giả của Công trình Nghiên cứu cấp Thành phố Hải Phòng năm 2018 về điều trị Hội chứng thận hư kháng thuốc ở Trẻ em.',
            'Tập huấn điều trị bệnh Covid 19 ở trẻ em',
            'Hướng dẫn nuôi con bằng sữa mẹ',
            'Chứng chỉ Nhi khoa DCH/IPPC (Sydney Child Health Program)',
            'Đạt chứng chỉ hồi sức cấp cứu Nhi khoa',
            'Giải Nhì cuộc thi “Bác Sĩ Tài Năng Trẻ” do Bệnh viện Nhi Đồng 2 tổ chức',
            'Bằng khen của UBND Thành phố Hồ Chí Minh trong công tác chống dịch COVID-19',
            'Giải thưởng Y học Lasker-DeBakey.',
            'Giải Fields cho Y học (Fields Medal in Medicine).',
            'Giải thưởng Hoàng gia Y học (Royal Society of Medicine Awards).',
        ];

        $titleArr = [
            'Tốt nghiệp năm 2020: Được Đào tạo và cấp Chứng chỉ Dinh dưỡng Nhi khoa',
            'Bảo vệ thành công luận án Thạc sĩ ',
            'Bảo vệ thành công luận án Tiến sĩ ',
            'Bảo vệ thành công luận án Giáo sư ',
            'Tốt nghiệp Bằng Nhi khoa Úc',
            'Bác sĩ Đa khoa',
            'Tốt nghiệp Bác sĩ đa khoa',
            'Tốt nghiệp Bác sĩ nội trú chuyên ngành Nhi khoa',
            'Tu nghiệp chuyên sâu về lĩnh vực sản phụ khoa, phẫu thuật nội soi, trẻ hóa da và trẻ hóa âm đạo',
            'Bác sĩ Chuyên khoa II Tai Mũi Họng',
            'Phẫu thuật nội soi mũi xoang',
            'Tham dự khóa huấn luyện quốc tế về Phẫu thuật Đầu cổ hằng năm lần thứ 11',
            'Lớp nâng cao phẫu thuật, chỉnh hình vùng hàm mặt, phẫu thuật tai - XTD',
            'Bác sĩ Nội trú Tai Mũi Họng',
            'Khóa huấn luyện phẫu tích xương thái dương tại Hà Nội',
        ];

        $languagesArr = [
            'Tiếng Việt',
            'Tiếng Anh',
            'Tiếng Tây Ban Nha',
            'Tiếng Pháp',
            'Tiếng Đức',
            'Tiếng Ý',
            'Tiếng Nhật',
            'Tiếng Trung',
            'Tiếng Hàn',
            'Tiếng Nga',
            'Tiếng Ả Rập',
            'Tiếng Bồ Đào Nha',
            'Tiếng Hà Lan',
            'Tiếng Thụy Điển',
            'Tiếng Đan Mạch',
            'Tiếng Phần Lan',
        ];

        $inforDoctors = InforDoctor::all();
        foreach ($inforDoctors as $index => $inforDoctor) {
            $information = '';
            $strengths = [];
            $language = [];

            $work_experience = [];
            $training_process = [];
            $awards_recognition = [];
            $research_work = [];

            $informationArrCopy = $informationsArr;
            $strengthsArrCopy = $strengthsArr;
            $languagesArrCopy = $languagesArr;
            $workplaceArrCopy = $workplaceArr;
            $positionArrCopy = $positionArr;
            $locationArrCopy = $locationArr;
            $titleArrCopy = $titleArr;
            $awardsRecognitionCopy = $awardsRecognitionArr;
            $researchWorkArrCopy = $researchWorkArr;

            for ($i = 0; $i <= 4; $i++) {
                $information .= $informationArrCopy[array_rand($informationArrCopy)];
                $strengths[] = $strengthsArrCopy[array_rand($strengthsArrCopy)];
                $language[] = $languagesArrCopy[array_rand($languagesArrCopy)];

                $start = 2012 + $i * 2;
                $end = 2012 + ($i + 1) * 2;

                $workplace = $workplaceArrCopy[array_rand($workplaceArrCopy)];
                $position = $positionArrCopy[array_rand($positionArrCopy)];
                $work_experience[] = [
                    'title' => $workplace,
                    'subtitle' => [$position, "$start - $end"],
                ];

                $location = $locationArrCopy[array_rand($locationArrCopy)];
                $title = $titleArrCopy[array_rand($titleArrCopy)];
                $training_process[] = [
                    'title' => $location,
                    'subtitle' => [$title, "$start - $end"],
                ];

                $awardsRecognition = $awardsRecognitionCopy[array_rand($awardsRecognitionCopy)];
                $awards_recognition[] = [
                    'title' => $awardsRecognition,
                    'subtitle' => ["$start"],
                ];

                $researchWork = $researchWorkArrCopy[array_rand($researchWorkArrCopy)];
                $research_work[] = [
                    'title' => $researchWork,
                    'subtitle' => ["$start"],
                ];

                // Loại bỏ các phần tử đã được chọn để không trùng lặp
                $informationArrCopy = array_diff($informationArrCopy, [$information]);
                $strengthsArrCopy = array_diff($strengthsArrCopy, [$strengths[count($strengths) - 1]]);
                $languagesArrCopy = array_diff($languagesArrCopy, [$language[count($language) - 1]]);
                $workplaceArrCopy = array_diff($workplaceArrCopy, [$workplace]);
                $positionArrCopy = array_diff($positionArrCopy, [$position]);
                $locationArrCopy = array_diff($locationArrCopy, [$location]);
                $titleArrCopy = array_diff($titleArrCopy, [$title]);
                $awardsRecognitionCopy = array_diff($awardsRecognitionCopy, [$awardsRecognition]);
                $researchWorkArrCopy = array_diff($researchWorkArrCopy, [$researchWork]);
            }

            $data = [
                'id_doctor' => $inforDoctor->id_doctor,
                'prominent' => json_encode($prominent),
                'information' => $information,
                'strengths' => json_encode($strengths),
                'language' => json_encode($language),
                'work_experience' => json_encode($work_experience),
                'training_process' => json_encode($training_process),
                'awards_recognition' => json_encode($awards_recognition),
                'research_work' => json_encode($research_work),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            InforExtendDoctor::create($data);
        }
    }
}
