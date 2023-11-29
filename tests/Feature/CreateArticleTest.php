<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateArticleTest extends TestCase
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

    public function testCreateArticleLackTitle()
    {
        Storage::fake('thumbnails');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => '',
            'content' => 'Nội dung bài viết mới của bệnh viện',
            'thumbnail' => $thumbnail,
            'id_category' => 1,
        ];
        $response = $this->post('api/article/add', $data, $this->getAuthorizationHeaders());
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Title is required',
            ],
        ]);
    }

    public function testCreateArticleLackContent()
    {
        Storage::fake('thumbnails');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Bài viết mới của bệnh viện',
            'content' => '',
            'thumbnail' => $thumbnail,
            'id_category' => 1,
        ];
        $response = $this->post('api/article/add', $data, $this->getAuthorizationHeaders());
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường nội dung không được bỏ trống.',
            ],
        ]);
    }

    public function testCreateArticleLackThumbnail()
    {
        $data = [
            'title' => 'Bài viết mới của bệnh viện',
            'content' => 'Nội dung bài viết mới của bệnh viện',
            'id_category' => 1,
        ];
        $response = $this->post('api/article/add', $data, $this->getAuthorizationHeaders());
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường thumbnail không được bỏ trống.',
            ],
        ]);
    }

    public function testCreateArticleSuccessful()
    {
        Storage::fake('thumbnails');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Bài viết mới của bệnh viện',
            'content' => 'Nội dung bài viết mới của bệnh viện',
            'thumbnail' => $thumbnail,
            'id_category' => 1,
        ];

        $response = $this->post('api/article/add', $data, $this->getAuthorizationHeaders());
        $response = $this->post('api/article/add', $data, $this->getAuthorizationHeaders());

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Thêm bài viết thành công !']);
    }
}
