<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for the Usuario DAO class (pure setters/getters + DB operations)
 */
class UsuarioModelTest extends TestCase
{
    private Usuario $usuario;

    protected function setUp(): void
    {
        $this->usuario = new Usuario();
    }

    public function testSetAndGetIdUser(): void
    {
        $this->usuario->setIdUser(42);
        $this->assertSame(42, $this->usuario->getIdUser());
    }

    public function testSetAndGetPhone(): void
    {
        $this->usuario->setPhone('9983194110');
        $this->assertSame('9983194110', $this->usuario->getPhone());
    }

    /**
     * setIdUser casts to int via intval
     */
    public function testIdUserIsInt(): void
    {
        $this->usuario->setIdUser('7');
        $this->assertIsInt($this->usuario->getIdUser());
        $this->assertSame(7, $this->usuario->getIdUser());
    }

    /**
     * DB connection test: consultar returns an array of users
     */
    public function testConsultarReturnsArray(): void
    {
        $result = $this->usuario->consultar();
        $this->assertIsArray($result);
        // The fixture has at least 2 users
        $this->assertGreaterThanOrEqual(2, count($result));
    }

    /**
     * DB connection test: consultar returns rows with the expected keys
     */
    public function testConsultarRowStructure(): void
    {
        $result = $this->usuario->consultar();
        $this->assertNotEmpty($result);
        $row = $result[0];
        $this->assertArrayHasKey('id_user', $row);
        $this->assertArrayHasKey('user_name', $row);
        $this->assertArrayHasKey('phone', $row);
        $this->assertArrayHasKey('email', $row);
    }

    /**
     * CRUD: create a user, read it back, update it, delete it
     */
    public function testFullCrudCycle(): void
    {
        $unique = 'test_' . uniqid();

        // Create
        $this->usuario->setName($unique);
        $this->usuario->setPhone('555000111');
        $this->usuario->setEmail($unique . '@test.com');
        $this->usuario->agregar();

        // Read — find our new user
        $all = $this->usuario->consultar();
        $created = null;
        foreach ($all as $row) {
            if ($row['user_name'] === $unique) {
                $created = $row;
                break;
            }
        }
        $this->assertNotNull($created, 'Created user not found in consultar()');
        $newId = (int)$created['id_user'];

        // Update
        $this->usuario->setIdUser($newId);
        $this->usuario->setName($unique . '_updated');
        $this->usuario->setPhone('555000222');
        $this->usuario->setEmail($unique . '_updated@test.com');
        $this->usuario->modificar();

        // Verify update
        $all = $this->usuario->consultar();
        $updated = null;
        foreach ($all as $row) {
            if ((int)$row['id_user'] === $newId) {
                $updated = $row;
                break;
            }
        }
        $this->assertNotNull($updated);
        $this->assertSame($unique . '_updated', $updated['user_name']);
        $this->assertSame('555000222', $updated['phone']);
        $this->assertSame($unique . '_updated@test.com', $updated['email']);

        // Delete
        $this->usuario->setIdUser($newId);
        $this->usuario->eliminar();

        // Verify deletion
        $all = $this->usuario->consultar();
        $deleted = null;
        foreach ($all as $row) {
            if ((int)$row['id_user'] === $newId) {
                $deleted = $row;
                break;
            }
        }
        $this->assertNull($deleted, 'User should have been deleted');
    }
}
