<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for the Control class (which wraps Usuario DAO)
 */
class ControlTest extends TestCase
{
    private Control $control;

    protected function setUp(): void
    {
        $this->control = new Control();
    }

    public function testAllUsuariosReturnsArray(): void
    {
        $result = $this->control->allUsuarios();
        $this->assertIsArray($result);
        $this->assertGreaterThanOrEqual(2, count($result));
    }

    public function testAllUsuariosRowStructure(): void
    {
        $result = $this->control->allUsuarios();
        $this->assertNotEmpty($result);
        $row = $result[0];
        $this->assertArrayHasKey('id_user', $row);
        $this->assertArrayHasKey('user_name', $row);
        $this->assertArrayHasKey('email', $row);
    }

    /**
     * altaUsuarios creates a user and returns JSON-encoded full list
     */
    public function testAltaUsuarios(): void
    {
        $unique = 'ctrl_test_' . uniqid();
        $json = $this->control->altaUsuarios([
            'name'  => $unique,
            'phone' => '555111222',
            'email' => $unique . '@test.com',
        ]);

        $result = json_decode($json, true);
        $this->assertIsArray($result);

        $created = null;
        foreach ($result as $user) {
            if ($user['user_name'] === $unique) {
                $created = $user;
                break;
            }
        }
        $this->assertNotNull($created, 'altaUsuarios did not return the created user');
        $this->assertSame('555111222', $created['phone']);
        $this->assertSame($unique . '@test.com', $created['email']);

        // Cleanup
        $this->deleteUserById($created['id_user']);
    }

    /**
     * editarUsuarios updates a user and returns JSON-encoded full list
     */
    public function testEditarUsuarios(): void
    {
        // First grab the first user
        $all = $this->control->allUsuarios();
        $target = $all[0];
        $origName = $target['user_name'];

        $updatedName = $origName . '_edited';
        $json = $this->control->editarUsuarios([
            'name'    => $updatedName,
            'phone'   => '999000000',
            'email'   => $target['email'],
            'id_user' => $target['id_user'],
        ]);

        $result = json_decode($json, true);
        $this->assertIsArray($result);

        $edited = null;
        foreach ($result as $user) {
            if ((int)$user['id_user'] === (int)$target['id_user']) {
                $edited = $user;
                break;
            }
        }
        $this->assertNotNull($edited);
        $this->assertSame($updatedName, $edited['user_name']);
        $this->assertSame('999000000', $edited['phone']);

        // Restore
        $this->control->editarUsuarios([
            'name'    => $origName,
            'phone'   => $target['phone'],
            'email'   => $target['email'],
            'id_user' => $target['id_user'],
        ]);
    }

    /**
     * eliminaUsuario deletes a user and returns JSON-encoded list
     */
    public function testEliminaUsuario(): void
    {
        // Create a temp user to delete
        $unique = 'del_test_' . uniqid();
        $this->control->altaUsuarios([
            'name' => $unique,
            'phone' => '555999888',
            'email' => $unique . '@test.com',
        ]);

        $all = $this->control->allUsuarios();
        $target = null;
        foreach ($all as $u) {
            if ($u['user_name'] === $unique) {
                $target = $u;
                break;
            }
        }
        $this->assertNotNull($target);

        $json = $this->control->eliminaUsuario(['id_user' => $target['id_user']]);
        $result = json_decode($json, true);
        $this->assertIsArray($result);

        $deleted = null;
        foreach ($result as $u) {
            if ((int)$u['id_user'] === (int)$target['id_user']) {
                $deleted = $u;
                break;
            }
        }
        $this->assertNull($deleted, 'User should no longer appear after eliminaUsuario');
    }

    private function deleteUserById(int $id): void
    {
        $usuario = new Usuario();
        $usuario->setIdUser($id);
        $usuario->eliminar();
    }
}
