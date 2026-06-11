<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for authentication: Admin DAO + loginUser + logoutUser
 *
 * These tests operate against the real `prueba.admins` table which
 * should contain the seeded admin user (admin / admin123).
 */
class AuthTest extends TestCase
{
    private Admin $adminDao;

    protected function setUp(): void
    {
        $this->adminDao = new Admin();
    }

    protected function tearDown(): void
    {
        // Clean up session side-effects
        $_SESSION = [];
    }

    public function testFindByUsernameReturnsRow(): void
    {
        $row = $this->adminDao->findByUsername('admin');
        $this->assertNotNull($row, 'admin user should exist in the admins table');
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('username', $row);
        $this->assertArrayHasKey('password', $row);
        $this->assertSame('admin', $row['username']);
    }

    public function testFindByUsernameReturnsNullForUnknown(): void
    {
        $row = $this->adminDao->findByUsername('nonexistent_' . uniqid());
        $this->assertNull($row);
    }

    public function testPasswordHashMatches(): void
    {
        $row = $this->adminDao->findByUsername('admin');
        $this->assertNotNull($row);
        $this->assertTrue(
            password_verify('admin123', $row['password']),
            'password_verify should match admin123 against the stored hash'
        );
    }

    public function testPasswordHashRejectsWrongPassword(): void
    {
        $row = $this->adminDao->findByUsername('admin');
        $this->assertNotNull($row);
        $this->assertFalse(
            password_verify('wrongpass', $row['password']),
            'wrong password should not verify'
        );
    }

    public function testLoginUserReturnsTrueForValidCredentials(): void
    {
        $result = loginUser('admin', 'admin123');
        $this->assertTrue($result, 'loginUser should return true for valid credentials');
        $this->assertArrayHasKey('admin_id', $_SESSION);
        $this->assertArrayHasKey('admin_user', $_SESSION);
        $this->assertSame('admin', $_SESSION['admin_user']);
    }

    public function testLoginUserReturnsFalseForWrongPassword(): void
    {
        $result = loginUser('admin', 'wrongpassword');
        $this->assertFalse($result, 'loginUser should return false for wrong password');
        $this->assertArrayNotHasKey('admin_id', $_SESSION,
            'session should not be set on failed login');
    }

    public function testLoginUserReturnsFalseForUnknownUser(): void
    {
        $result = loginUser('ghost_' . uniqid(), 'test123');
        $this->assertFalse($result, 'loginUser should return false for unknown user');
        $this->assertArrayNotHasKey('admin_id', $_SESSION);
    }

    public function testLogoutUserClearsSession(): void
    {
        // First login
        loginUser('admin', 'admin123');
        $this->assertArrayHasKey('admin_id', $_SESSION);

        // Then logout
        logoutUser();

        // After session_destroy, the $_SESSION superglobal still exists
        // as an empty array in the current request
        $this->assertArrayNotHasKey('admin_id', $_SESSION,
            'logoutUser should clear admin_id from session');
    }

    public function testLoginRegeneratesSessionId(): void
    {
        $oldId = session_id();
        session_write_close();

        loginUser('admin', 'admin123');
        $newId = session_id();

        $this->assertNotSame($oldId, $newId,
            'session_regenerate_id should produce a new session ID');
    }
}
