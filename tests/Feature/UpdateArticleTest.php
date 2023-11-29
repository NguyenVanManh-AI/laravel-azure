<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    public $token = '';

    protected function setUp(): void
    {
        parent::setUp();

        $data = [
            'email' => 'benhviengiadinh@yopmail.com',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $responseData = $response->json();
        $this->assertArrayHasKey('access_token', $responseData['data']);
        $this->token = $responseData['data']['access_token'];
    }

    private function getAuthorizationHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }

    public function testUpdateArticleLackTitle()
    {
        $article = Article::orderBy('id', 'desc')->first();

        Storage::fake('thumbnails');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => '',
            'content' => 'Cập nhật Nội dung bài viết mới của bệnh viện',
            'thumbnail' => $thumbnail,
            'id_category' => 2,
        ];

        $response = $this->post('api/article/update/' . $article->id, $data, $this->getAuthorizationHeaders());

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường tiêu đề phải là một chuỗi kí tự.',
                'Trường tiêu đề phải có tối thiểu 1 kí tự.',
            ],
        ]);
    }

    public function testUpdateArticleLackContent()
    {
        $article = Article::orderBy('id', 'desc')->first();

        Storage::fake('thumbnails');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Cập nhật Bài viết mới của bệnh viện',
            'content' => '',
            'thumbnail' => $thumbnail,
            'id_category' => 2,
        ];

        $response = $this->post('api/article/update/' . $article->id, $data, $this->getAuthorizationHeaders());

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường nội dung phải là một chuỗi kí tự.',
                'Trường nội dung phải có tối thiểu 1 kí tự.',
            ],
        ]);
    }

    public function testUpdateArticleSuccessful()
    {
        $article = Article::orderBy('id', 'desc')->first();

        Storage::fake('thumbnails');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Cập nhật Bài viết mới của bệnh viện',
            'content' => 'Cập nhật Nội dung bài viết mới của bệnh viện',
            'thumbnail' => $thumbnail,
            'id_category' => 2,
        ];

        $response = $this->post('api/article/update/' . $article->id, $data, $this->getAuthorizationHeaders());

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Cập nhật bài viết thành công !']);
    }
}
