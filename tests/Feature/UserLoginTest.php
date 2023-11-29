<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserLoginTest extends TestCase
{
    public function testUserLoginLackEmail()
    {
        $data = [
            'email' => '',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường email không được bỏ trống.',
            ],
        ]);
    }

    public function testUserLoginLackPassword()
    {
        $data = [
            'email' => 'benhviengiadinh@yopmail.com',
            'password' => '',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường mật khẩu không được bỏ trống.',
            ],
        ]);
    }

    public function testUserLoginEmailMalformed()
    {
        $data = [
            'email' => 'benhviengiadinh9999',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation errors',
            'data' => [
                'Trường email phải là một địa chỉ email hợp lệ.',
            ],
        ]);
    }

    public function testUserLoginEmailNotExist()
    {
        $data = [
            'email' => 'benhviengiadinh9999@yopmail.com',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Email không tồn tại !']);
    }

    public function testUserLoginEmailOrPasswordIncorrect()
    {
        $data = [
            'email' => 'benhviengiadinh@yopmail.com',
            'password' => '1234567',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Email hoặc mật khẩu không chính xác !']);
    }

    public function testUserLoginAccountNotAccept()
    {
        $data = [
            'email' => 'benhvienkimkhanh@yopmail.com',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Tài khoản của bạn đã bị khóa hoặc chưa được phê duyệt !']);
    }

    public function testUserLoginAccountNotConfirm()
    {
        $data = [
            'email' => 'benhvienvietnhat@yopmail.com',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Email này chưa được xác nhận , hãy kiểm tra và xác nhận nó trước khi đăng nhập !']);
    }

    public function testUserLoginSuccessful()
    {
        $data = [
            'email' => 'benhviengiadinh@yopmail.com',
            'password' => '123456',
        ];
        $response = $this->post('api/user/login', $data);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Đăng nhập thành công !']);
    }
}
