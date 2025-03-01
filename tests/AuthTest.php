<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase {
    private $auth;
    private $db;

    protected function setUp(): void {
        parent::setUp();
        $this->auth = new AuthController();
        $this->db = Database::getInstance();
        
        // تنظيف قاعدة البيانات للاختبار
        $this->db->exec("DELETE FROM users WHERE email LIKE 'test%@example.com'");
    }

    public function testSuccessfulRegistration() {
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'full_name' => 'Test User'
        ];

        $result = $this->auth->register($userData);
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('تم إنشاء الحساب بنجاح', $result['message']);
    }

    public function testDuplicateEmailRegistration() {
        $userData = [
            'username' => 'testuser1',
            'email' => 'test1@example.com',
            'password' => 'password123',
            'full_name' => 'Test User 1'
        ];

        // التسجيل الأول
        $this->auth->register($userData);
        
        // محاولة التسجيل بنفس البريد الإلكتروني
        $result = $this->auth->register($userData);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('البريد الإلكتروني مسجل مسبقاً', $result['message']);
    }

    public function testSuccessfulLogin() {
        $userData = [
            'username' => 'testuser2',
            'email' => 'test2@example.com',
            'password' => 'password123',
            'full_name' => 'Test User 2'
        ];

        // إنشاء مستخدم جديد
        $this->auth->register($userData);
        
        // محاولة تسجيل الدخول
        $result = $this->auth->login($userData['email'], $userData['password']);
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('تم تسجيل الدخول بنجاح', $result['message']);
    }

    public function testFailedLogin() {
        $result = $this->auth->login('nonexistent@example.com', 'wrongpassword');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('البريد الإلكتروني أو كلمة المرور غير صحيحة', $result['message']);
    }

    public function testLogout() {
        $result = $this->auth->logout();
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('تم تسجيل الخروج بنجاح', $result['message']);
    }

    protected function tearDown(): void {
        // تنظيف بعد الاختبارات
        $this->db->exec("DELETE FROM users WHERE email LIKE 'test%@example.com'");
        parent::tearDown();
    }
} 