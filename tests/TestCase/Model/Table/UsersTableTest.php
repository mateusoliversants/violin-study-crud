<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\TestSuite\TestCase;
use Cake\Http\Client;
use Cake\Http\Client\Response;
use Cake\TestSuite\HttpClientTrait;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = $this->getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    public function testSaveUser(): void
    {
        $usuario = [
            'id' => 3,
            'email' => 'novousuario@mail.com',
            'password' => '1b',
            'created' => '2026-01-15 16:56:45'
        ];

        $user = $this->Users->newEntity($usuario);
        $result = $this->Users->save($user);

        $this->assertNotFalse($result);
    }

    public function testEmailDuplicado(): void
    {
        $usuario = [
            'email' => 'test1@mail.com',
            'password' => '123ab',
            'created' => '2026-04-15 17:33:28'
        ];

        $user = $this->Users->newEntity($usuario);
        $result = $this->Users->save($user);

        $this->assertFalse($result);
    }

    public function testEmailVazio(): void
    {
        $usuario = [
            'email' => '',
            'password' => '12ab',
            'created' => '2026-04-17 17:33:28'
        ];

        $user = $this->Users->newEntity($usuario);

        $this->assertNotEmpty($user->getErrors());
    }

    public function testTamanhoSenha(): void
    {
        $senha = str_repeat('a1', 200);

        $usuario = [
            'email' => 'senha255@mail.com',
            'password' => $senha,
            'created' => '2026-08-07 11:21:08'
        ];

        $user = $this->Users->newEntity($usuario);

        $this->assertNotEmpty($user->getErrors());
    }

    public function testSenhaVazia(): void
    {
        $usuario = [
            'email' => 'vazio@mail.com',
            'password' => '',
            'created' => '2026-04-17 17:33:28'
        ];

        $user = $this->Users->newEntity($usuario);

        $this->assertNotEmpty($user->getErrors());
    }

    public function testSenhaLetraNumero(): void
    {
        $usuario = [
            'email' => 'letra@mail.com',
            'password' => '123',
            'created' => '2026-08-11 18:23:15'
        ];

        $user = $this->Users->newEntity($usuario);

        $this->assertNotEmpty($user->getErrors());
    }

    public function testNotGmail(): void
    {
        $usuario = [
            'email' => 'nao@gmail.com',
            'password' => '1a',
            'created' => '2026-09-01 22:11:18'
        ];

        $user = $this->Users->newEntity($usuario);

        $this->assertNotEmpty($user->getErrors());
    }

    use HttpClientTrait;

    public function testCadastrarAssinante()
    {
        $this->mockClientPost(
            'http://localhost/onboarding/v1/2a5d7400f3b1d2876bee4938d89d9e24/api-assinantes.json',
            $this->newClientResponse(200, [], json_encode(['id' => 12345]))
        );

        $entity = $this->Users->newEntity([
            'email' => 'test@email.com'
        ]);

        $result = $this->Users->cadastrarAssinante($entity);

        $this->assertEquals(12345, $result);
        $this->assertEquals(12345, $entity->assinante_id);
    }

    public function testCadastrarUsuarioAssinante()
    {
        $this->mockClientPost(
            'http://localhost/onboarding/v1/2a5d7400f3b1d2876bee4938d89d9e24/api-usuario-assinantes.json',
            $this->newClientResponse(200, [], json_encode(['id' => 999]))
        );

        $entity = $this->Users->newEntity([
            'email' => 'test@email.com',
            'password' => '12345A'
        ]);

        $result = $this->Users->cadastrarUsuarioAssinante($entity);

        $this->assertEquals(999, $result);
        $this->assertEquals(999, $entity->usuario_assinante_id);
    }

    public function testAlterarSenha()
    {
        $this->mockClientPost(
            'http://localhost/onboarding/v1/2a5d7400f3b1d2876bee4938d89d9e24/api-usuarios-assinantes/99.json',
            $this->newClientResponse(200, [], json_encode(['ok' => true]))
        );

        $entity = $this->Users->newEntity([
            'email' => 'test@email.com',
            'plain_password' => '12Ab',
            'usuario_assinante_id' => '99'
        ]);

        $result = $this->Users->alterarSenha($entity);

        $this->assertTrue($result);
    }
}
