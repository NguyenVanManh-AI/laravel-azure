<?php

namespace Database\Seeders;

use App\Models\Category;
use Database\Factories\FakeImageFactory;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoriesSeeder extends Seeder
{
    // public function run()
    // {
    //     $categories = [
    //         'Sức khỏe răng miệng',
    //         'Dược liệu',
    //         'Tâm lý - Tâm thần',
    //         'Thể dục thể thao',
    //         'Lão hóa lành mạnh',
    //         'Thói quen lành mạnh',
    //         'Ăn uống lành mạnh',
    //         'Ung thư - Ung bướu',
    //         'Bệnh về não & hệ thần kinh',
    //         'Bệnh truyền nhiễm',
    //         'Bệnh tiêu hóa',
    //         'Bệnh về máu',
    //         'Sức khỏe tình dục',
    //         'Da liễu',
    //         'Dị ứng',
    //         'Chăm sóc giấc ngủ',
    //         'Bệnh tai mũi họng',
    //         'Bệnh cơ xương khớp',
    //         'Bệnh thận và Đường tiết niệu',
    //         'Bệnh hô hấp',
    //         'Bệnh tim mạch',
    //         'Tiểu đường',
    //         'Sức khỏe mắt',
    //         'Thuốc và thực phẩm chức năng',
    //         'Mang thai',
    //         'Sức khỏe phụ nữ',
    //         'Sức khỏe',
    //         'Sức khỏe nam giới',
    //         'Nuôi dạy con',
    //     ];

    //     foreach ($categories as $category) {
    //         DB::table('categories')->insert([
    //             'name' => $category,
    //             'thumbnail' => 'storage/image/thumbnail/category.png',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }
    // }

    public function run()
    {
        $pathFolder = 'public/storage/image/thumbnail/categories';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $categories = [
            [
                'name' => 'Sức khỏe răng miệng',
                'description_category' => 'Khoang miệng của chúng ta chứa đầy vi khuẩn và chúng thường vô hại. Việc không chăm sóc răng miệng đúng cách có thể khiến vi khuẩn tăng sinh mất kiểm soát, dẫn đến các bệnh về răng miệng. Hãy tìm hiểu cách cải thiện sức khỏe răng miệng và bảo vệ bản thân chống lại các bệnh khác ngay bây giờ.',
            ],
            [
                'name' => 'Dược liệu',
                'description_category' => 'Đừng bỏ qua chuyên mục này nếu bạn cần tìm hiểu về công dụng, cách dùng và hình ảnh các thảo dược Việt Nam cũng như những dược liệu khác.',
            ],
            [
                'name' => 'Tâm lý - Tâm thần',
                'description_category' => 'Nhiều vấn đề về tâm lý - tâm thần nghiêm trọng có thể không được phát hiện sớm. Biết cách duy trì tinh thần khỏe mạnh và cách nhận biết yếu tố nguy cơ, dấu hiệu bệnh tâm lý sẽ hữu ích cho bạn và người thân.',
            ],
            [
                'name' => 'Thể dục thể thao',
                'description_category' => 'Thể dục thể thao là hoạt động không thể thiếu nếu bạn muốn có tinh thần sảng khoái, cơ thể khỏe mạnh! Tập thể dục mỗi ngày cũng giúp bạn phòng ngừa bệnh tật. Bạnhãy chọn môn thể dục yêu thích và tập luyện mỗi ngày.',
            ],
            [
                'name' => 'Lão hóa lành mạnh',
                'description_category' => 'Lão hóa là quá trình tự nhiên của cuộc sống mà bất kỳ ai cũng đều trải qua. Hiểu rõ những thay đổi của cơ thể về mặt thể chất và tinh thần theo thời gian sẽ giúp bạn giữ gìn sức khỏe tốt hơn, đồng thời giảm đi những lo lắng không đáng có. Cùng Hello Bacsi tìm hiểu về lão hóa lành mạnh ngay sau đây.',
            ],
            [
                'name' => 'Thói quen lành mạnh',
                'description_category' => 'Thói quen lành mạnh giúp bạn sống khỏe mạnh, hạnh phúc hơn. Trước khi bắt đầu tạo lập những thói quen tốt, bạn cần biết cách nhận biết những thói quen nào tốt hoặc không tốt cho sức khỏe thể chất chất và tinh thần ở đây.',
            ],
            [
                'name' => 'Ăn uống lành mạnh',
                'description_category' => 'Chế độ ăn uống có tác động lớn đến sức khỏe tổng thể và khả năng ngăn ngừa bệnh tật của một người. Người có chế độ ăn uống lành mạnh thường sống khỏe, trẻ lâu. Làm thế nào để xây dựng và tạo lập thói quen ăn uống khoa học?',
            ],
            [
                'name' => 'Ung thư - Ung bướu',
                'description_category' => 'Tùy từng người, ung thư có thể biểu hiện khác nhau và không phân biệt đối tượng hay độ tuổi. Cùng tìm hiểu tất cả thông tin về ung thư như nguyên nhân, yếu tố nguy cơ, triệu chứng, phương pháp chẩn đoán, điều trị và phòng ngừa tại đây.',
            ],
            [
                'name' => 'Bệnh về não & hệ thần kinh',
                'description_category' => 'Hệ thần kinh kiểm soát não, tủy sống và hàng nghìn tỷ dây thần kinh. Khi hệ thống này bị ảnh hưởng, người bệnh có thể phải đối mặt với những rối loạn thần kinh như co giật, chứng phình động mạch hoặc u não. Tìm hiểu thêm các tình trạng ảnh hưởng đến não và hệ thần kinh cũng như biện pháp ngăn ngừa, điều trị ngay tại đây.',
            ],
            [
                'name' => 'Bệnh truyền nhiễm',
                'description_category' => 'Cơ thể chúng ta là "ngôi nhà" của vô vàn vi sinh vật khác nhau. Dù thường vô hại nhưng chúng vẫn có thể đe dọa sức khỏe. Hãy tìm hiểu thêm về các bệnh truyền nhiễm do vi khuẩn, virus, nấm hoặc ký sinh trùng tại đây.',
            ],
            [
                'name' => 'Bệnh tiêu hóa',
                'description_category' => 'Hầu hết dưỡng chất cơ thể cần đều phải bổ sung bằng thực phẩm, do đó hệ tiêu hóa có vai trò rất quan trọng. Miệng, dạ dày, ruột non và ruột già, gan, thận, tuyến tụy, túi mật... phải làm việc cùng nhau để đảm bảo hệ tiêu hóa hoạt động hiệu quả. Cùng tìm hiểu các vấn đề thường gặp ở đường ruột và cách điều trị, bảo vệ sức khỏe tiêu hóa tại đây.',
            ],
            [
                'name' => 'Bệnh về máu',
                'description_category' => 'Chế độ ăn uống có tác động lớn đến sức khỏe tổng thể và khả năng ngăn ngừa bệnh tật của một người. Người có chế độ ăn uống lành mạnh thường sống khỏe, trẻ lâu. Làm thế nào để xây dựng và tạo lập thói quen ăn uống khoa học?',
            ],
            [
                'name' => 'Sức khỏe tình dục',
                'description_category' => 'Tình dục là một phần không thể thiếu để duy trì hạnh phúc hôn nhân. Sức khỏe tình dục là những thông tin khoa học liên quan đến bệnh tình dục, cách bảo vệ, phòng ngừa, điều trị, các tư thế quan hệ, cách quan hệ dễ lên đỉnh... để bạn có đời sống tình dục an toàn.',
            ],
            [
                'name' => 'Da liễu',
                'description_category' => 'Một làn da khỏe mạnh có ý nghĩa quan trọng trong việc điều chỉnh thân nhiệt, bảo vệ cơ thể khỏi nguy cơ nhiễm trùng.  Mời bạn tìm hiểu thêm cách bảo vệ da; những tình trạng da phổ biến và cách chăm sóc da tại đây.',
            ],
            [
                'name' => 'Dị ứng',
                'description_category' => 'Dị ứng là một tình trạng phổ biến, xảy ra khi hệ miễn dịch phản ứng với chất gây dị ứng, như thực phẩm, phấn hoa hoặc lông thú cưng. Tìm hiểu về các loại dị ứng và cách đối phó với các tình trạng này tại đây.',
            ],
            [
                'name' => 'Chăm sóc giấc ngủ',
                'description_category' => 'Giấc ngủ có vai trò vô cùng quan trọng đối với sức khỏe. Thế nhưng, trong xã hội hiện đại, thật khó để chúng ta duy trì được giấc ngủ lành mạnh. Vậy làm sao để chăm sóc giấc ngủ tốt hơn? Những thông tin dưới đây sẽ hữu ích cho bạn.',
            ],
            [
                'name' => 'Bệnh tai mũi họng',
                'description_category' => 'Tai mũi họng là một phần của đường hô hấp trên, giúp chúng ta thở, ngửi, nghe, giữ thăng bằng, nuốt và nói. Làm sao giữ tai mũi họng luôn khỏe mạnh, đâu là những tình trạng sức khỏe có thể ảnh hưởng đến tai mũi họng? Tìm hiểu thêm tại đây.',
            ],
            [
                'name' => 'Bệnh cơ xương khớp',
                'description_category' => 'Càng nhiều tuổi, hệ cơ xương khớp càng trải qua quá trình hao mòn nhất định, gây đau khớp, đau lưng cũng như các chấn thương cơ xương khớp khác, làm ảnh hưởng hoặc hạn chế khả năng vận động. Cùng tìm hiểu thêm về những tình trạng sức khỏe có thể tác động đến hệ thống cơ xương khớp của bạn ngay tại đây.',
            ],
            [
                'name' => 'Bệnh thận và Đường tiết niệu',
                'description_category' => 'Một trong những chức năng chính của hệ tiết niệu là lọc máu và tạo ra các sản phẩm phụ như nước tiểu sau khi cơ thể đã hấp thu hết dưỡng chất. Để duy trì sức khỏe hệ tiết niệu, bạn cần giữ cho thận, niệu quản, niệu đạo và bàng quang luôn trong trạng thái hoạt động hiệu quả nhất.',
            ],
            [
                'name' => 'Bệnh hô hấp',
                'description_category' => 'Phổi và hệ hô hấp phối hợp đưa oxy trong không khí vào cơ thể, đồng thời loại bỏ carbon dioxide khi thở ra, giúp duy trì các chức năng quan trọng. Tìm hiểu cách tăng cường sức khỏe hệ hô hấp và bảo vệ phổi tại đây.',
            ],
            [
                'name' => 'Bệnh tim mạch',
                'description_category' => 'Tim giúp bơm máu đi khắp cơ thể để nuôi các cơ quan khác. Nếu tim có bất kỳ sự gián đoạn hay suy yếu nào, sức khỏe có thể bị ảnh hưởng nghiêm trọng. Tìm hiểu cách tăng cường và bảo vệ sức khỏe tim mạch ngay tại đây.',
            ],
            [
                'name' => 'Tiểu đường',
                'description_category' => 'Bệnh tiểu đường có thể gây ảnh hưởng nghiêm trọng đến sức khỏe, làm suy giảm các chức năng quan trọng của cơ thể. Hãy tìm hiểu về các loại bệnh tiểu đường, triệu chứng, yếu tố nguy cơ và cách điều trị tại đây.',
            ],
            [
                'name' => 'Sức khỏe mắt',
                'description_category' => 'Mắt là một trong những cơ quan cảm giác phát triển nhất trong cơ thể. Ta phụ thuộc vào thị lực để có thể thực hiện hầu hết các hoạt động hàng ngày. Vì vậy, việc duy trì sức khỏe đôi mắt tốt là điều cần được ưu tiên. Tìm hiểu những vấn đề có thể ảnh hưởng đến thị lực và cách ngăn ngừa bệnh lý về mắt ngay tại đây.',
            ],
            [
                'name' => 'Thuốc và thực phẩm chức năng',
                'description_category' => 'Thuốc được thiết kế để chẩn đoán, điều trị, và ngăn chặn bệnh lý, với các loại kê đơn và không kê đơn. Trong khi đó, thực phẩm chức năng như vitamin, khoáng chất, thảo dược, và dinh dưỡng bổ sung, được tạo ra để bổ sung chế độ ăn hàng ngày và hỗ trợ sức khỏe tổng thể. Cả hai loại sản phẩm đều đóng vai trò quan trọng trong duy trì và cải thiện sức khỏe, mỗi loại có ứng dụng và cơ địa khác nhau.',
            ],
            [
                'name' => 'Mang thai',
                'description_category' => 'Kể từ lúc mang thai đến khi sinh con, bạn sẽ trải qua nhiều điều thú vị cùng vô vàn những khó khăn, thách thức. Hãy tìm hiểu để có sự chuẩn bị giúp đảm bảo một thai kỳ an toàn và khỏe mạnh ngay tại đây.',
            ],
            [
                'name' => 'Sức khỏe phụ nữ',
                'description_category' => 'Một người phụ nữ khỏe mạnh, biết cách chăm sóc bản thân sẽ xinh đẹp và hạnh phúc. Kiến thức chăm sóc sức khỏe phụ nữ từ độ tuổi bắt đầu hành kinh đến khi mãn kinh sẽ giúp bạn biết cách chăm sóc bản thân trước nhiều tình trạng khác nhau như mất cân bằng nội tiết, nhiễm trùng...',
            ],
            [
                'name' => 'Sức khỏe',
                'description_category' => 'Bạn cần tìm tin tức y khoa, bảo hiểm y tế, những kinh nghiệm khám chữa bệnh tại các phòng khám và bệnh viện trên toàn quốc? Đây là chuyên mục dành cho bạn. Ngoài ra, bạn còn có thể đọc thêm nhiều thông tin thú vị về sức khỏe ngay tại đây.',
            ],
            [
                'name' => 'Sức khỏe nam giới',
                'description_category' => 'Các vấn đề về sinh lý, đời sống tinh thần, sự thay đổi nội tiết... đều có thể ảnh hưởng đến sức khỏe nam giới. Dưới đây là những tình trạng quan trọng và phổ biến mà cánh mày râu cần lưu tâm.',
            ],
            [
                'name' => 'Nuôi dạy con',
                'description_category' => 'Là cha mẹ bạn cần biết cách nuôi dạy, chăm sóc để con phát triển tốt, khỏe mạnh và vui vẻ. Tại đây, bạn sẽ tìm thấy những thông tin hữu ích, lời khuyên về cách nuôi dạy con phù hợp.',
            ],
        ];

        // Cách 1 : Chậm
        // $thumbnails = [];

        // lệnh tạo ảnh đôi lúc có cái ảnh tạo được , có cái tạo không được nên phải dùng lệnh while
        // để đến khi nào có ảnh mới thôi

        // foreach ($categoryNames as $index => $categoryName) {
        //     $thumbnail = FakeImageFactory::new()->createThumbnailCategory();
        //     while (!$thumbnail) {
        //         $thumbnail = FakeImageFactory::new()->createThumbnailCategory();
        //     }
        //     $thumbnails[$index] = 'storage/image/thumbnail/categories/' . $thumbnail;
        // }

        // foreach ($categoryNames as $index => $categoryName) {
        //     Category::create([
        //         'name' => $categoryName,
        //         'thumbnail' => $thumbnails[$index],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Cách 2 : Nhanh hơn rất nhiều
        $pathFolder = 'storage/app/public/image/thumbnail/categories/';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }
        foreach ($categories as $index => $category) {
            try {
                while (true) {
                    $client = new Client;
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $thumbnail = $pathFolder . $nameImage;
                    if (file_put_contents($thumbnail, $imageContent)) {
                        Category::create([
                            'name' => $category['name'],
                            'description_category' => $category['description_category'],
                            'thumbnail' => 'storage/image/thumbnail/categories/' . $nameImage,
                            'search_number' => random_int(0, 300),
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
