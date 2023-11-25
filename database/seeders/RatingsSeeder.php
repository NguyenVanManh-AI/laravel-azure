<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;

class RatingsSeeder extends Seeder
{
    public function run()
    {
        $ratings_advise = [
            [
                'detail_rating' => 'Tư vấn tốt đấy !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Bác sĩ tư vấn rất tận tình !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Bác sĩ rất tâm lí !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Tôi thấy bác sĩ của bệnh viện này tư vấn rất tốt !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Tôi rất hài lòng !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Tôi thấy cũng tốt đấy !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bác sĩ cũng chu đáo đấy !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bác sĩ có chuyên môn cao !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bác sĩ hiểu được tâm lí và tình trạng của bệnh nhân !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Tôi thấy tư vấn ở đây cũng bình thường !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Tôi thấy không có gì , cũng bình thường !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Tôi thấy bác sĩ có chuyên môn chưa cao !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Bác sĩ chưa cởi mở lắm !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Bác sĩ tư vấn nhưng tôi chưa hiểu lắm !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Lịch hẹn tư vấn thì trễ , bác sĩ thiếu chuyên môn !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Bác sĩ còn thiếu chuyên môn !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Bác sĩ còn thiếu kinh nghiệm , tư vấn nhưng tôi chưa hiểu lắm !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Bác sĩ chưa tận tâm , chưa tư vấn nhiệt tình !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Tôi chưa hài lòng lắm !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Tư vấn quá tệ !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Tư vấn tệ hết sức , mọi người không nên book lịch này !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Tôi không hài lòng !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Tôi không hài lòng một chút nào !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Tôi thấy bác sĩ còn thiếu chuyên môn và nên xem lại thái độ khi tư vấn !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Tư vấn quá kém !',
                'number_rating' => 1,
            ],
        ];

        $ratings_service = [
            [
                'detail_rating' => 'Dịch vụ tốt đấy !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Tôi thấy dịch vụ ở đây tốt nhất trong các nơi tôi đã làm !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Cơ sở vật chất sạch sẽ và hiện đại !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Nhân viên phục vụ nhiệt tình và thân thiện !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Quá trình chuẩn bị và tiếp nhận bệnh nhân rất chuyên nghiệp !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Thời gian chờ đợi không lâu !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Dịch vụ ở đây cũng tốt nhưng giá cả hơi cao !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bệnh viện cần cải thiện về đội ngũ nhân viên để phục vụ tốt hơn !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Cơ sở vật chất cần nâng cấp !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Không hài lòng với quá trình làm dịch vụ của bác sĩ !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Bác sĩ làm việc vội vã, không tận tâm !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Gặp một số vấn đề về tổ chức dịch vụ !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Dịch vụ ở đây cần cải thiện nhiều hơn !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Bệnh viện cần nâng cao chất lượng phục vụ người bệnh !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Bệnh viện cần cải thiện về sự linh hoạt trong việc đặt lịch và thời gian làm dịch vụ !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh ổn định, không có gì nổi bật !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Không hài lòng với cách thức giải quyết khiếu nại của bệnh viện !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Bác sĩ gặp nhiều vấn đề về tư duy nhất quán !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh chưa đạt đến tiêu chuẩn y tế !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Nhân viên tiếp tân cần được đào tạo thêm về thái độ phục vụ !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Bệnh viện cần cải thiện về sự tự động hóa trong quá trình làm dịch vụ !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Quá trình làm dịch vụ của bác sĩ không chuyên nghiệp !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh ở đây quá tệ, tôi không bao giờ quay lại !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Bệnh viện cần cải thiện về việc duy trì và nâng cao chất lượng dịch vụ !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Không hài lòng với sự thiếu chuyên nghiệp trong quá trình điều trị !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh ở đây không đáp ứng được mong đợi !',
                'number_rating' => 1,
            ],
            [
                'detail_rating' => 'Bệnh viện cần nâng cấp hệ thống ghi chú và lưu trữ thông tin bệnh nhân !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Dịch vụ ở đây cần cải thiện về tính minh bạch và thông tin cho bệnh nhân !',
                'number_rating' => 2,
            ],
            [
                'detail_rating' => 'Thời gian đợi và thủ tục làm dịch vụ cần được tối ưu hóa !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh đôi khi gặp khó khăn trong việc đáp ứng nhu cầu khẩn cấp !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Bác sĩ cần cải thiện khả năng giao tiếp với bệnh nhân !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh cần cải thiện về sự chăm sóc sau điều trị !',
                'number_rating' => 3,
            ],
            [
                'detail_rating' => 'Bệnh viện cần cải thiện về việc duy trì sự an toàn trong quá trình làm dịch vụ !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Dịch vụ ở đây tốt nhưng cần cải thiện thêm về tính tiện lợi !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bác sĩ rất am hiểu và giải thích rõ ràng về tình trạng sức khỏe của tôi !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh ở đây rất chuyên nghiệp và hiệu quả !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Tôi rất hài lòng với tốc độ giải quyết các thủ tục và làm dịch vụ tại bệnh viện !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Cơ sở vật chất đạt chuẩn, sạch sẽ và tiện nghi !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Quá trình chuẩn bị và làm dịch vụ của bác sĩ ở đây rất chất lượng !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Dịch vụ tận tâm, đội ngũ y tế nhiệt tình và chuyên nghiệp !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Bệnh viện này thực sự là lựa chọn số 1 của tôi !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Thời gian chờ đợi ngắn, tư vấn tận tình, tôi cảm thấy được quan tâm đến !',
                'number_rating' => 5,
            ],
            [
                'detail_rating' => 'Dịch vụ khám chữa bệnh ở đây rất hiện đại và tiện lợi !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bác sĩ làm dịch vụ nhanh chóng, chính xác và thân thiện !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bệnh viện này cung cấp dịch vụ chăm sóc sau điều trị rất tốt !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Cảm ơn bác sĩ và đội ngũ y tế, tôi đã có trải nghiệm làm dịch vụ tuyệt vời !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Dịch vụ ở đây không chỉ chất lượng mà còn mang đến sự thoải mái và tin tưởng !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bệnh viện này đáp ứng đúng những gì tôi mong đợi về dịch vụ y tế !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Dịch vụ chăm sóc bệnh nhân rất tận tâm, làm tôi cảm thấy an tâm khi điều trị tại đây !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Thực sự rất hài lòng với quá trình điều trị và phục vụ tại bệnh viện này !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Dịch vụ ở đây không chỉ chăm sóc tốt về y tế mà còn về tâm lý bệnh nhân !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Bác sĩ và nhân viên y tế ở đây rất nhiệt tình và chuyên nghiệp !',
                'number_rating' => 4,
            ],
            [
                'detail_rating' => 'Dịch vụ đáp ứng đầy đủ các yêu cầu và mong đợi của tôi !',
                'number_rating' => 4,
            ],
        ];

        $workScheduleAdvise = WorkSchedule::whereNull('id_service')->whereNotNull('id_user')->where('is_confirm', 1)->get();
        foreach ($workScheduleAdvise as $ws) {
            $rating = (object) $ratings_advise[array_rand($ratings_advise)];
            $data = [
                'id_work_schedule' => $ws->id,
                'id_user' => $ws->id_user,
                'detail_rating' => $rating->detail_rating,
                'number_rating' => $rating->number_rating,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            Rating::create($data);
        }

        $workScheduleService = WorkSchedule::whereNotNull('id_service')->whereNotNull('id_user')->where('is_confirm', 1)->get();
        foreach ($workScheduleService as $ws) {
            $rating = (object) $ratings_service[array_rand($ratings_service)];
            $data = [
                'id_work_schedule' => $ws->id,
                'id_user' => $ws->id_user,
                'detail_rating' => $rating->detail_rating,
                'number_rating' => $rating->number_rating,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            Rating::create($data);
        }
    }
}
