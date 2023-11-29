<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
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

    public function testDeleteArticleSuccessful()
    {
        $article = Article::orderBy('id', 'desc')->first();
        $response = $this->delete('api/article/delete/' . $article->id, $this->getAuthorizationHeaders());
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Xóa bài viết thành công !']);
    }
}
