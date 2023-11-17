<?php

namespace Database\Seeders;

use App\Models\HospitalDepartment;
use App\Models\HospitalService;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HospitalServicesSeeder extends Seeder
{
    public function run()
    {
        // try {
        $services = [
            [
                'name' => 'Nội soi đại tràng',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Nội soi đại tràng là thủ thuật nhằm mục đích hỗ trợ các y bác sĩ quan sát toàn bộ chiều dài của đại tràng và trực tràng, tầm soát các bất thường trong ruột cũng như khối u thịt nhỏ nằm trên thành đại tràng.<br /><br />Bệnh nhân có thể tiến hành nội soi đại tràng nhằm mục đích tìm kiếm nguyên nhân các dấu hiệu và triệu chứng đường ruột, tầm soát ung thư đại tràng, xem xét bệnh nhân có polyp đại tràng hay không.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Quá trình nội soi đại tràng thường mất khoảng từ 30 - 45 phút. Các bác sĩ sẽ đưa một ống nhựa dẻo có gắn camera và đèn vào hậu môn, quan sát các cấu trúc bên trong ruột cũng như tìm kiếm các tổn thương như viêm hoặc polyp.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Bạn không nên ăn thức ăn cứng khô trước khi tiến hành nội soi.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Bác sĩ có thể đề nghị uống thuốc nhuận tràng dưới dạng thuốc viên hoặc dạng lỏng.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Trong một số trường hợp, bạn có thể cần phải sử dụng bộ bơm trực tràng (hay còn gọi là thụt tháo đại tràng) để làm sạch đại tràng vào đêm trước hoặc vài tiếng trước khi khám.</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">• Quá trình nội soi đại tràng thường mất khoảng từ 30 - 45 phút. Các bác sĩ sẽ tiến hành đưa thiết bị nội soi vào hậu môn, bơm không khí vào ruột già làm cho đoạn ruột phồng ra nhờ vậy có thể quan sát các cấu trúc bên trong ruột một cách rõ hơn.</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 1,
            ],
            [
                'name' => 'Nội soi dạ dày',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Nội soi dạ dày là kỹ thuật nhằm mục đích hỗ trợ các y bác sĩ có thể quan sát các cơ quan của ống tiêu hóa (thực quản, dạ dày, tá tràng) nhằm phục vụ cho quá trình chẩn đoán và điều trị bệnh.<br /><br />Quá trình nội soi được thực hiện khi các y bác sĩ sẽ tiến hành dùng ống nội soi chuyên dụng có gắn camera đi qua cổ họng, xuống dạ dày để xem xét, đánh giá các tổn thương bên trong thông qua hình ảnh trên màn hình máy tính.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Bệnh nhân có thể được chỉ định nội soi nhằm mục đích tầm soát các triệu chứng bất thường như: chảy máu bất thường, khó nuốt, đau bụng, nghi ngờ mắc các vấn đề bệnh lý liên quan đến dạ dày,...</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Trước khi tiến hành nội soi dạ dày, bệnh nhân cần nhịn ăn trước khi tiến hành thủ thuật khoảng 6 giờ. Tuy nhiên, bạn có thể uống một ít nước trước đó khoảng 2 giờ.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Nếu bệnh nhân đang trong quá trình sử dụng các loại thuốc làm loãng máu, hãy thông báo cho bác sĩ khi tiến hành thực hiện thủ thuậ</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">"• Quá trình nội soi dạ dày kéo dài khoảng 15 phút. Thông thường, bệnh nhân sẽ được tiến hành nội soi trong trạng thái tỉnh táo. Nếu cần thiết bạn sẽ được sử dụng thuốc gây mê để khi thực hiện thủ thuật.
                        
                        • Bệnh nhân sẽ được hướng dẫn tư thế nằm phù hợp, các bác sĩ tiến hành đưa thiết bị nội soi vào miệng, xuống cổ họng và đến thực quản, tầm soát các vấn đề bất thường thông qua hình ảnh trên màn hình. "</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 1,
            ],
            [
                'name' => 'Gói khám sức khỏe tổng quát định kỳ',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Khám sức khỏe tổng quát định kỳ là biện pháp hữu hiệu giúp tầm soát các căn bệnh nguy hiểm từ giai đoạn sớm, hỗ trợ bảo vệ sức khỏe, phòng ngừa các trường hợp bệnh lý nguy hiểm có thể xảy ra.<br /><br />Theo khuyến cáo của Tổ chức Y Tế thế giới, người từ 18 tuổi trở lên cần được khám sức khỏe định kỳ 6 tháng/ lần hoặc 1 năm/ lần nhằm phát hiện và điều trị sớm các bệnh lý đang trong giai đoạn tiềm ẩn. Quá trình khám sức khỏe tổng quát bao gồm các bước như sàng lọc, kiểm tra chức năng tim mạch, hô hấp, các nguy cơ ung thư phổi, dạ dày, vòm họng hay các bệnh viêm gan siêu vi,…</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Bên cạnh quá trình thăm khám, bệnh nhân còn được tiến hành thực hiện các xét nghiệm như: Xét nghiệm máu, mỡ máu, đường máu, gout, đánh giá chức năng gan, thận, xét nghiệm nước tiểu, chụp X-quang ngực, siêu âm tuyến giáp, ổ bụng và đo điện tim.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Quý bệnh nhân cần chuẩn bị đầy đủ các loại giấy tờ tùy thân quan trọng, các kết quả xét nghiệm và kết quả khám trước đó, các đơn thuốc đã và đang dùng.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Trước khi thực hiện các loại siêu âm phần phụ, siêu âm bụng, nên uống thật nhiều nước và nhịn tiểu trước đó 1 giờ.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Với các xét nghiệm máu, xét nghiệm đường huyết, bệnh nhân cần nhịn ăn trước khi tiến hành xét nghiệm ít nhất 8 tiếng.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Người mắc bệnh đái tháo đường khi làm xét nghiệm kiểm tra sức khỏe tổng quát không nên uống thuốc insulin vào buổi sáng ngày đi khám.</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Bệnh nhân được tiến hành:
                        
                        • Khám lâm sàng
                        • Thực hiện thăm do chức năng: Đo điện tim, đo mật độ xương,...
                        • Chẩn đoán hình ảnh: Siêu âm ổ bụng, siêu âm tuyến giáp, chụp X-Quang tim phổi,...
                        • Xét nghiệm huyết học: Công thức máu 24 chỉ số, định nhóm máu,...
                        • Xét nghiệm sinh hóa máu
                        • Xét nghiệm chức năng gan, mật
                        • Xét nghiệm chức năng chuyển hóa: Đường huyết, Cholesterol, Acid Uric
                        • Xét nghiệm tình trạng dinh dưỡng
                        • Xét nghiệm sinh hóa nước tiểu
                        • Tầm soát viêm gan
                        • Xét nghiệm chỉ điểm ung thư sớm
                        • Xét nghiệm đông máu</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 1,
            ],
            [
                'name' => 'Đo loãng xương',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Loãng xương là tình trạng suy giảm mật độ xương khiến xương trở nên yếu giòn và dễ gãy hơn. Phần lớn các trường hợp loãng xương đều không có biểu hiện lâm sàng cho đến khi xảy ra tình trạng gãy xương. Vì thế, đo loãng xương chính là biện pháp cần thiết nhằm dự đoán và giảm nguy cơ gãy xương trong tương lai.<br /><br />Quá trình đo loãng xương diễn ra tương đối nhanh chóng và đơn giản mất khoảng từ 20 - 30 phút. Sau khi tiến hành đo loãng xương, các y bác sĩ sẽ đọc kết quả, so sánh các thông số về mật độ xương.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Bệnh nhân có thể tiến hành đo loãng xương trong trường hợp có kết quả chụp X-quang thấy có chỗ bị thiếu xương, gãy xương ở cột sống, hay bị đau lưng và nguy cơ gãy đốt sống. Ngoài ra, bất cứ khi nào cảm thấy bản thân gặp các vấn đề về xương khớp, nên tiến hành gặp các bác sĩ chuyên khoa để được thăm khám, đánh giá và có được phác đồ điều trị đúng đắn hiệu quả.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Bệnh nhân không nên đeo đồ trang sức kim loại hoặc mặc quần áo có các chi tiết kim loại khi tiến hành thực hiện đo loãng xương.</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Bác sĩ sẽ hướng dẫn bệnh nhân nằm trên giường đệm của máy đo. Quá trình đo thường diễn ra trong khoảng 20 - 30 phút. Sau quá trình đo, các y bác sĩ sẽ tiến hành đọc kết quả, so sánh các thông số và tư vấn về biện pháp phòng ngừa cũng như điều trị cho bệnh nhân.</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 1,
            ],
            [
                'name' => 'Dịch vụ tẩy trắng răng',
                'infor' => json_encode([
                    'about_service' => '<p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Tẩy trắng răng là kỹ thuật nha khoa thẩm mỹ phổ biến giúp cải thiện các vấn đề liên quan đến độ trắng sáng, đều màu của răng.<br /><br />Phương pháp được áp dụng dành cho trường hợp khách hàng gặp các vấn đề như răng bị xỉn màu bởi các chất kích thích như rượu bia, thuốc lá, răng ố vàng do vệ sinh không hiệu quả hoặc do nhiễm trùng màu kháng sinh mức độ 1 và 2.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai gói dịch vụ Tẩy trắng răng nội tủy (1 răng), tẩy trắng răng tại chỗ 1 hàm, tẩy trắng răng tại chỗ bằng đèn Zoom 2. Bên cạnh đó, với hệ thống trang thiết bị hiện đại, quy trình tẩy trắng răng rành mạch, quý khách hàng cũng có thể chọn lựa hình thức tẩy trắng răng tại nhà hoặc tẩy trắng răng tại phòng khám.</span></p><br /></p>',
                    'prepare_process' => '<p></p>
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Khách hàng có thể trao đổi với các y bác sĩ về tình trạng bệnh lý răng miệng đã và đang mắc phải (nếu có) trước khi tiến hành tẩy trắng răng.</span>
                            <br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" />
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Một số trường hợp như phụ nữ có thai, đang cho con bú, hoặc những người có cấu trúc của thân răng không tốt, tuyệt đối không nên làm trắng răng, vì chắc chắn sẽ ảnh hưởng tới nướu, tụt lợi hoặc nứt răng gây ê buốt và kích thích tủy. Nên đến các nha khoa uy tín để được các bác sĩ xem xét, chỉ định có nên làm trắng răng hay không.</span>
                            <br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" />
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Không nên sử dụng ngay lập tức những đồ uống như trà, cà phê, thực phẩm có phẩm màu sau khi vừa tiến hành tẩy trắng răng.&nbsp;</span>',
                    'service_details' => '<p></p>
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Dịch vụ Tẩy trắng răng tại phòng khám Đa khoa Quốc tế Sài Gòn bao gồm các hạng mục như:
                            • Tẩy trắng răng nội tuỷ (1 răng)
                            • Tẩy trắng tại chỗ 1 hàm
                            • Tẩy trắng tại chổ bằng đèn Zoom 2
                            • Tẩy trắng tại nhà
                            • Tẩy trắng tại phòng khám loại 2</span>',
                    'location' => [19, 29],
                ]),
                'id_department' => 2,
            ],
            [
                'name' => 'Trám răng kẽ hở 2 răng',
                'infor' => json_encode([
                    'about_service' => '<p></p>
                            <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Trám răng thưa là phương pháp sử dụng các vật liệu trám nhằm thu hẹp các kẽ hở của răng một cách tự nhiên.<br /><br />Phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai gói dịch vụ Trám răng kẽ hở 2 răng nhằm cải thiện các vấn đề liên quan đến tính thẩm mỹ, đảm bảo chức năng ăn nhai của răng.</span></p>
                            <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Quy trình trám răng thưa diễn ra tương đối nhanh chóng và tiết kiệm chi phí. Khoảng hở được lấp đầy nhờ răng 2 bên được trám rộng ra, nhưng vẫn được tách biệt bởi một khe hẹp ở giữa một cách tự nhiên.</span></p>',
                    'prepare_process' => '<p>
                            </p>
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Quá trình trám răng diễn ra tương đối nhanh chóng và đơn giản, không gây cảm giác đau đớn, vì thế bạn hoàn toàn có thể thoải mái trước khi tiến hành trám răng.</span>
                            <br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" />
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Không nên ăn ngay sau khi tiến hành trám. Có thể ăn sau khoảng từ 1 - 2 giờ nhằm giúp cho vật liệu trám đông cứng hoàn toàn và đảm bảo hiệu quả chỗ trám.</span>',
                    'service_details' => '<p></p>
                            <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Quy trình trám răng:
                            • Bác sĩ tiến hành thăm khám và tư vấn
                            • Gây tê và vệ sinh chỗ cần trám
                            • Tiến hành trám
                            • Kiểm tra, chỉnh sửa chỗ trám và hoàn tất</span>',
                    'location' => [19, 29],
                ]),
                'id_department' => 2,
            ],
            [
                'name' => 'Cạo vôi răng',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Cao răng hay vôi răng là những mảng bám thức ăn trên kẽ răng đã bị vôi hóa. Cạo vôi răng là phương pháp nha khoa giúp làm sạch, vệ sinh răng miệng, loại bỏ các mảng cao răng hình thành trên răng.<br /><br />Tình trạng cao răng lâu ngày không lấy có thể gây ảnh hưởng đến tính thẩm mỹ, tăng nguy cơ hôi miệng, ê buốt, sâu răng và các vấn đề bệnh lý như viêm nướu, nghiêm trọng hơn là viêm nha chu.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai gói dịch vụ lấy cao răng chia theo từng cấp độ 1, cấp độ 2 và cấp độ 3. Dịch vụ được tiến hành thực hiện bởi đội ngũ y bác sĩ dày dặn kinh nghiệm, cùng hệ thống trang thiết bị hiện đại tại phòng khám.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Trẻ em dưới 10 tuổi không nên lấy cao răng vì lúc này răng vẫn chưa hoàn thiện, gây ảnh hưởng đến các răng mới mọc.</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Thai phụ không nên tiến hành lấy cao răng ở giai đoạn đầu và giai đoạn cuối của thai kỳ để đảm bảo sức khỏe thai nhi</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Phòng khám hiện đang triển khai các hạng mục dịch vụ lấy cao răng:
                        • Lấy cao răng cấp 1: 500,000 VNĐ
                        • Lấy cao răng cấp 2: 400,000 VNĐ
                        • Lấy cao răng cấp 3: 300,000 VNĐ
                        • Lấy cao răng trẻ em: 250,000 VNĐ</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 2,
            ],
            [
                'name' => 'Nhổ răng khôn',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Răng khôn là răng mọc cuối cùng và thường xuất hiện ở độ tuổi từ 17 đến 25. Việc răng khôn xuất hiện và phải tiến hành nhổ bỏ vẫn luôn khiến nhiều người nghi ngại bởi mức độ đau và nguy cơ biến chứng.<br /><br />Với lợi thế sở hữu trang thiết bị hiện đại cùng đội ngũ y bác sĩ chuyên khoa dày dặn kinh nghiệm, phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai các hạng mục nhổ răng khôn vĩnh viễn với những nhóm răng: nhóm răng khôn lệch, nhóm răng khôn ngầm, nhóm răng khôn thẳng,...</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Quá trình được thực hiện nghiêm ngặt, giảm bớt tối đa cảm giác đau, kiểm soát biến chứng và hỗ trợ khách hàng trong việc hồi phục sau quá trình nhổ răng khôn.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Trước khi tiến hành nhổ răng khôn, khách hàng cần lưu ý:</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Vệ sinh răng miệng sạch sẽ</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Lấy sạch cao răng, mảng bám trên răng</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Không uống rượu bia trước khi nhổ răng một ngày</span><br style=" color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px" /><span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Nắm rõ tiền sử bệnh của bản thân, các loại thuốc đang sử dụng để trao đổi cùng các bác sĩ</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai các loại hình nhổ răng khôn như:
                        • Nhổ răng khôn vĩnh viễn nhóm răng khôn lệch
                        • Nhổ răng khôn vĩnh viễn nhóm răng khôn ngầm
                        • Nhổ răng khôn vĩnh viễn nhóm răng khôn thẳng</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 2,
            ],
            [
                'name' => 'Khám Da liễu',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Các triệu chứng, vấn đề bệnh lý da liễu không chỉ ảnh hưởng đến sức khỏe mà còn gây cảm giác khó chịu, bất tiện trong sinh hoạt hằng ngày. Việc tiến hành thăm khám và có phác đồ điều trị từ sớm là điều cần thiết, tránh để tình trạng trở nặng.<br /><br />Phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai dịch vụ Khám Da liễu bao gồm quá trình thăm khám và đánh giá các vấn đề như: viêm da dị ứng, viêm da tiếp xúc, bệnh vẩy nến, nổi mề đay, các tình trạng mụn khác nhau,...</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Quy trình thăm khám được thực hiện bởi đội ngũ y bác sĩ dày dặn kinh nghiệm tại phòng khám. Bệnh nhân được chẩn đoán về tình trạng bệnh hiện tại, tư vấn về phác đồ điều trị cũng như biện pháp phòng ngừa, tránh để tình trạng bệnh tái phát.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Quy trình đặt lịch thăm khám qua nền tảng Hello Bacsi:&nbsp;&nbsp;&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Bước 1: Quý bệnh nhân tiến hành chọn thời gian và đặt lịch khám trong khung "Đặt lịch hẹn".&nbsp;&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Bước 2: Sau khi hoàn tất đặt lịch, Hello Bacsi sẽ gửi email xác nhận thông tin lịch hẹn khám cho bệnh nhân.&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Bước 3: Bệnh nhân đến bệnh viện/phòng khám theo lịch hẹn, đưa email xác nhận cho đội ngũ lễ tân/y tá và tiến hành thăm khám.</p>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Dịch vụ bao gồm các hạng mục thăm khám và điều trị các bệnh ngoài da như:
                        
                        • Viêm da dị ứng
                        • Các tình trạng mụn
                        • Mề đay, bệnh vẩy nến,...
                        • Các bệnh lây nhiễm nấm ngoài da,...</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 3,
            ],
            [
                'name' => 'Khám da mặt cùng bác sĩ chuyên khoa Da liễu',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Viện Thẩm mỹ Lux Beauty Center hiện đang triển khai dịch vụ thăm khám da mặt miễn phí cùng bác sĩ chuyên khoa Da liễu.<br /><br />Trong suốt quá trình thăm khám, quý khách hàng sẽ được tư vấn về tình trạng da mặt, đặc điểm riêng của từng loại da (da thường, da khô, da dầu, da hỗn hợp, da nhạy cảm), mụn trên da, phương pháp chăm sóc da, cách lựa chọn sản phẩm chăm sóc phù hợp.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Thêm vào đó, quý khách hàng cũng có thể trao đổi về các vấn đề sắc tố, tuyến bã nhờn trên da, độ ẩm, tông da,...Việc nắm được tình trạng da từ sớm giúp hỗ trợ phát hiện các vấn đề mụn ẩn, nám mà bạn có thể gặp phải.<br /><br />Dịch vụ cần thiết với tất cả đối tượng khách hàng có nhu cầu thăm khám da mặt, đặc biệt khi bạn đang ở tuổi dậy thì, chị em phụ nữ trước và sau sinh hoặc đang bước vào giai đoạn tiền mãn kinh.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">• Quý khách hàng có thể chuẩn bị trước các thông tin cần giải đáp về tình trạng da mặt.&nbsp;&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">• Có thể ghi chú hoặc ghi nhớ các loại sản phẩm chăm sóc da hiện đang sử dụng.</p>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Khám và tư vấn các vấn đề:
                        
                        • Đặc điểm da mặt
                        • Tình trạng mụn
                        • Độ ẩm, tông da, tuyến bã nhờn
                        • Phương pháp chăm sóc da
                        • Sản phẩm chăm sóc phù hợp
                        
                        Viện Thẩm mỹ Lux Beauty Center hiện đang triển khai gói dịch vụ khám da mặt miễn phí cùng bác sĩ chuyên khoa Da liễu.
                        Giá gốc: 550,000 VNĐ</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 3,
            ],
            [
                'name' => 'Điều trị nám - Nanoskin',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Nám là một trong những vấn đề phổ biến liên quan đến sắc tố ở da. Có nhiều phương pháp điều trị nám da khác nhau như sử dụng thuốc thoa, thuốc uống, ứng dụng laser điều trị hoặc tiêm vi điểm.<br /><br />Về quá trình điều trị nám, đội ngũ y bác sĩ tại Lux Beauty Center sẽ tiến hành thăm khám, soi và phân tích tình trạng nám từ đó đưa ra phương pháp điều trị phù hợp.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Về phương pháp điều trị nám bằng laser, các bác sĩ sử dụng laser Fotona Q-switched Nd:Yag 1064 nm - thiết bị laser được nhập từ châu Âu có bước sóng 1064 nm được sử dụng rất nhiều trong điều trị nám. Tia laser này có thể đi sâu trong lớp bì và phá vỡ các hạt sắc tố thành những mảnh nhỏ, từ đó cơ thể có thể dễ dàng hấp thu và đào thải mà không làm tổn thương lớp thượng bì phía trên.<br /><br />Bên cạnh đó, quý khách hàng cũng có thể được kết hợp các phương pháp điều trị nám khác nhằm tối ưu hóa hiệu quả điều trị và giảm tác dụng phụ.<br /><br />• Gói tầm soát hiện đang có giá ưu đãi đặc biệt: Giảm 80% từ 2,500,000 VNĐ =&gt; 499,000 VNĐ.<br />• Ưu đãi đặc biệt kéo dài đến hết ngày 30.11.2023<br /><br />QUÀ TẶNG KÈM THEO: Điều trị Nám tặng Soi da trị giá 550,000 VNĐ</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">• Quý khách hàng có thể chuẩn bị trước các thông tin cần giải đáp về tình trạng da mặt.</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">• Có thể ghi chú hoặc ghi nhớ các loại sản phẩm chăm sóc da hiện đang sử dụng.</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">• Sau khi mua voucher gói khám, quý khách hàng sẽ nhận được<span>&nbsp;</span><strong style=" font-style: inherit; font-variant: inherit; font-weight: 600; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">email xác nhận đơn mua</strong><span>&nbsp;</span>từ Hello Bacsi. Nếu có bất kỳ thắc mắc vào về quy trình thăm khám, đội ngũ Chăm sóc khách hàng tại Hello Bacsi luôn sẵn sàng hỗ trợ.</p>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Gói dịch vụ điều trị nám bao gồm các hạng mục:
                        • Soi và phân tích tình trạng nám
                        • Tư vấn phương pháp điều trị phù hợp (Thuốc thoa, thuốc uống, sử dụng laser, tiêm vi điểm).
                        
                        * Thời hạn sử dụng gói khám trong vòng 30 ngày kể từ ngày thanh toán
                        * Chi phí gói khám đã thanh toán không thể hoàn trả</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 3,
            ],
            [
                'name' => 'Điều trị nám, tàn nhang',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Dịch vụ điều trị nám, tàn nhang tại phòng khám Lux Beauty Center áp dụng công nghệ QX-Max thế hệ mới, hỗ trợ điều trị nám da bước sóng, với hiệu suất tối đa. Bên cạnh đó, công nghệ QX-Max còn đem lại thêm các lợi ích khác cho da như sản sinh collagen, kích thích tuần hoàn máu, thu nhỏ lỗ chân lông và làm sáng da.<br /><br />Đối tượng sử dụng dịch vụ này bao gồm khách hàng gặp vấn đề về da như nám mảng, nám sâu, nám hỗn hợp, muốn da trắng sáng tự nhiên, da không đều màu, tàn nhang hay đốm nâu trên da.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Khách hàng sẽ được trải qua các bước kiểm tra, thăm khám và phân tích da để xác định chính xác tình trạng nám của da. Sau đó, bác sĩ sẽ thiết kế riêng một liệu trình điều trị phù hợp với khách hàng. Mỗi liệu trình điều trị sẽ chỉ từ 15 – 20 phút, hơi châm chít nhẹ, không gây bỏng rác cũng như tác dụng phụ. Đặc biệt, khách hàng thể sinh hoạt như bình thường ngay sau khi điều trị.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Đầu tiên, các bác sĩ sẽ tiến hành kiểm tra tình trạng da của khách hàng thông qua quan sát và máy soi da. Sau đó, bác sĩ sẽ tiến hành thăm khám và tư vấn phương pháp điều trị và liệu trình phù hợp với thể trạng của từng khách hàng cụ thể để có kết quả tốt nhất.</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Dịch vụ Điều trị nám, tàn nhang sẽ được tiến hành theo trình tự:
                        - Bác sĩ chuyên môn da liễu tư vấn và thăm khám trực tiếp
                        - Làm sạch da trước khi điều trị
                        - Tiến hành điều trị bằng Laser
                        - Chăm sóc da sau điều trị
                        - Theo dõi sau điều trị</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 3,
            ],
            [
                'name' => 'Khám mắt tổng quát',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Phòng khám Đa khoa Quốc tế Sài Gòn hiện đang triển khai dịch vụ Khám mắt hỗ trợ bệnh nhân tầm soát, kiểm tra các vấn đề về thị lực, bệnh lý Nhãn khoa.<br /><br />Trong suốt quá trình thăm khám, đội ngũ y bác sĩ sẽ tiến hành đánh giá thị lực cũng như xem xét các vấn đề bất thường về mắt. Tùy theo tìnhh trạng, các bác sĩ sẽ chỉ định nhiều loại sàng lọc khác nhau như: đo nhãn áp, kiểm tra tật khúc xạ, đo thị trường nhìn, kiểm tra cấu trúc trong mắt, soi đáy mắt,...</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Dịch vụ được thực hiện bởi đội ngũ y bác sĩ chuyên khoa dày dặn kinh nghiệm. Bên cạnh quá trình tầm soát, các bác sĩ cũng sẽ tiến hành tư vấn nhiệt tình cho khách hàng cách thức chăm sóc và bảo vệ mắt, phòng ngừa các vấn đề bệnh lý Nhãn khoa.</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Quy trình đặt lịch thăm khám qua nền tảng Hello Bacsi:&nbsp;&nbsp;&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Bước 1: Quý bệnh nhân tiến hành chọn thời gian và đặt lịch khám trong khung "Đặt lịch hẹn".&nbsp;&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Bước 2: Sau khi hoàn tất đặt lịch, Hello Bacsi sẽ gửi email xác nhận thông tin lịch hẹn khám cho bệnh nhân.&nbsp;&nbsp;</p><p style=" margin: 8px 0px 16px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: inherit; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: rgb(38, 38, 38)">Bước 3: Bệnh nhân đến bệnh viện/phòng khám theo lịch hẹn, đưa email xác nhận cho đội ngũ lễ tân/y tá và tiến hành thăm khám.</p>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Khách hàng được tiến hành thăm khám tổng quát, kiểm tra tình trạng thị lực, các vấn đề liên quan đến, tật khúc xạ, bệnh lý Nhãn khoa.</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 4,
            ],
            [
                'name' => 'Khám mắt cơ bản',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Gói dịch vụ Khám mắt cơ bản nhằm mục đích hỗ trợ khách hàng kiểm tra mắt định kỳ, sàng lọc các tình trạng nghi ngờ bệnh lý như đau mắt đỏ hoặc bệnh nhân tái khám sau khi thực hiện phẫu thuật điều trị Nhãn khoa.<br /><br />Về chi tiết, gói khám bao gồm các hạng mục như: khám mắt cùng bác sĩ chuyên khoa, đo thị lực chủ quan, khám mắt bằng sinh hiển vi (slit-lamp).</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Trung tâm Mắt Tinh Anh cung cấp dịch vụ xét nghiệm toàn diện với hệ thống các máy móc tiên tiến từ các hãng nước ngoài. Quý khách hàng có thể tiến hành thăm khám nhằm tầm soát các tình trạng bệnh lý thông thường như: viêm kết mạc dị ứng, viêm kết mạc siêu vi, kiểm tra dị vật tại mắt.<br /><br />Ngoài ra, dịch vụ cũng phù hợp với khách hàng có nhu cầu tái khám thông thường sau khi thực hiện phẫu thuật (mộng, quặm, phaco,...).</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Trường hợp tái khám sau phẫu thuật, quý khách hàng có thể mang theo hồ sơ bệnh án cũ, đơn thuốc nếu có nhằm giúp đội ngũ y bác sĩ nắm được tình trạng rõ ràng hơn. • Trường hợp nghi ngờ mắc các bệnh lý về mắt, cần ghi nhớ hoặc ghi chú các triệu chứng bất thường đang gặp phải, tần suất xuất hiện.</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Gói khám bao gồm các hạng mục như:
                        
                        • Thăm khám cùng bác sĩ chuyên khoa
                        • Đo thị lực chủ quan
                        • Khám mắt bằng sinh hiển vi (slit-lamp)</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 4,
            ],
            [
                'name' => 'Khám mắt trẻ sinh non (ROP)',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Bệnh võng mạc ở trẻ sinh non là tình trạng rối loạn mắt xuất hiện chủ yếu ở trẻ sơ sinh có cân nặng khoảng 1250 gram hoặc ít hơn được sinh ra trước tuần 31 của thai kỳ (thai kỳ đầy đủ là 38-42 tuần).<br /><br />Dịch vụ nhằm mục đích hỗ trợ thăm khám, kiểm tra các triệu chứng bệnh võng mạc ở trẻ như: chuyển động mắt bất thường, mắt lác, cận thị nặng, đồng tử màu trắng,...</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Bệnh có khả năng cải thiện và không để lại hậu quả lâu dài trường hợp bệnh võng mạc ở trẻ sinh non thể nhẹ. Khoảng 90% bệnh võng mạc ở trẻ sinh non được kết luận là nhẹ và không cần điều tr nhưng cũng có không ít trường hợp nghiêm trọng biến chứng dẫn đến mù lòa. Vì thế, để chắc chắn về tình trạng bệnh, các bậc phụ huynh cần tiến hành đưa bé thăm khám khi gặp phải các vấn đề về mắt, đặc</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">Các triệu chứng của bệnh võng mạc ở trẻ sinh non nghiêm trọng bao gồm: • Chuyển động mắt bất thường • Mắt lác • Cận thị nặng • Đồng tử màu trắng Bố mẹ cần tiến hành đưa bé đi thăm khám khi gặp phải các tình trạng như trên.</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Đội ngũ y bác sĩ sẽ tiến hành thăm khám về tình trạng của bé, sàng lọc đánh giá các triệu chứng, chẩn đoán và tư vấn về phương thức điều trị.</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 4,
            ],
            [
                'name' => 'Đo khúc xạ',
                'infor' => json_encode([
                    'about_service' => '<p>

                        <p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline">Đo khúc xạ hay đo tật khúc xạ là quá trình kiểm tra độ khúc xạ của mắt nhằm xác định mắt có mắc các tình trạng tật khúc xạ như cận, viễn, loạn hay lão thị hay không và xác định chính xác tật khúc xạ nếu có.<br /><br />Bạn có thể thực hiện đo khúc xạ trường hợp cảm giác thị lực đang suy giảm có khả năng mắc các tật khúc xạ, nhạy cảm với ảnh sáng, thường xuyên mỏi mắt.</span></p><p data-size="base" data-type="regular" class="wIj6fkD " style=" font-variant-numeric: inherit; font-variant-east-asian: inherit; font-variant-alternates: inherit; font-variant-position: inherit; font-stretch: inherit; line-height: 24px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; vertical-align: baseline; color: var(--custom-color,#262626)"><span style=" font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-size: 16px; letter-spacing: inherit; vertical-align: baseline"><br />Trung tâm Mắt Tinh Anh hiện đang triển khai các hình thức đo khúc xạ:<br />• Đo khúc xạ (không liệt điều tiết): 80,000 VNĐ<br />• Đo khúc xạ (liệt điều tiết): 100,000 VNĐ</span></p>
                        <br /></p>',
                    'prepare_process' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px">• Bạn có trao đổi cùng bác sĩ chuyên khoa trường hợp mắt có các biểu hiện bất thường về thị lực, độ nhạy cảm với ánh sáng, thường xuyên bị mỏi mắt,...</span>
                        <br /></p>',
                    'service_details' => '<p>

                        <span style="color: rgb(38, 38, 38); font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Helvetica Neue&quot;, sans-serif; font-size: 16px; white-space: pre-line">Quy trình đo khúc xạ mắt bao gồm: đo thị lực bằng bảng thị lực và đo độ khúc xạ bằng máy khúc xạ tự động. Sau khi đo, bạn sẽ nhận được bảng kết quả và nhận tư vấn từ bác sĩ chuyên khoa mắt.
                        
                        Trung tâm Mắt Tinh Anh hiện đang triển khai các hình thức đo khúc xạ:
                        • Đo khúc xạ (không liệt điều tiết): 80,000 VNĐ
                        • Đo khúc xạ (liệt điều tiết): 100,000 VNĐ</span>
                        <br /></p>',
                    'location' => [19, 29],
                ]),
                'id_department' => 4,
            ],
        ];

        $pathFolder = 'public/storage/image/thumbnail/services';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $hospitals = User::where('role', 'hospital')->limit(3)->get();
        // $hospitals = User::where('role', 'hospital')->get();
        $pathFolder = 'storage/app/public/image/thumbnail/services/';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }
        foreach ($hospitals as $index => $hospital) {
            // $ids = HospitalDepartment::where('id_hospital', $hospital->id)->whereIn('id_department',[1, 2, 3, 4])->get()->pluck('id');
            foreach ($services as $index => $service) {
                while (true) {
                    $client = new Client; // LƯU Ý : Khai báo biến trong while để tránh lỗi
                    // do ta chỉ khai báo 1 connect sau đó gọi nhiều lần thì nó sẽ lỗi
                    // ta nên khai báo nhiều connect
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $thumbnail = $pathFolder . $nameImage;
                    if (file_put_contents($thumbnail, $imageContent)) {
                        $id_hospital_department = HospitalDepartment::where('id_hospital', $hospital->id)->where('id_department', $service['id_department'])->first()->id;
                        unset($service['id_department']); // loại bỏ ra khỏi mảng trước khi insert vào database
                        // vì ta dùng array_merge toàn bộ biến $service nên nếu không loại bỏ id_department khỏi $service
                        // thì sẽ gây lỗi vì trong table hospital_service không có column này
                        $data = array_merge(
                            $service,
                            [
                                'id_hospital_department' => $id_hospital_department,
                                'thumbnail_service' => 'storage/image/thumbnail/services/' . $nameImage,
                                // 'time_advise' => random_int(1, 4) * 30, // để cho bên workschedule dễ seed
                                'time_advise' => 30,
                                'search_number_service' => random_int(0, 300),
                                'price' => random_int(1, 60) * 50000,
                                'is_delete' => false,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                        HospitalService::create($data);
                        break;
                    }
                }
            }
        }
        // } catch (\Exception $e) {
        // }
    }
}
