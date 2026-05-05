<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class WebhooksControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected $fixtures = [
        'app.Users',
    ];

    public function testUsuarioCriacao()
    {
        $data = [
            'evento' => 'usuario_criacao',
            'id' => 999,
            'email' => 'criar@email.com',
            'assinantes' => [
                [
                    'id' => 123
                ]
            ]
        ];

        $this->post('/webhooks/index', $data);

        $this->assertResponseOk();

        $users = $this->getTableLocator()->get('Users');

        $user = $users->find()
            ->where([
                'usuario_assinante_id' => 999
            ])
            ->first();

        $this->assertNotEmpty($user);

        $this->assertEquals('criar@email.com', $user->email);

        $this->assertEquals(123, $user->assinante_id);
    }

    public function testUsuarioAlteracao()
    {
        $users = $this->getTableLocator()->get('Users');

        $user = $users->newEntity([
            'email' => 'antigo@email.com',
            'password' => '123aBc',
            'usuario_assinante_id' => 555
        ]);

        $users->save($user);

        $data = [
            'evento' => 'usuario_alteracao',
            'id' => 555,
            'email' => 'novo@email.com'
        ];

        $this->post('/webhooks/index', $data);

        $this->assertResponseOk();

        $updatedUser = $users->find()
            ->where([
                'usuario_assinante_id' => 555
            ])
            ->first();

        $this->assertEquals(
            'novo@email.com',
            $updatedUser->email
        );
    }

    public function testUsuarioExclusao()
    {
        $users = $this->getTableLocator()->get('Users');

        $user = $users->newEntity([
            'email' => 'exclusao@email.com',
            'password' => '123Abc',
            'usuario_assinante_id' => 777
        ]);

        $users->save($user);

        $data = [
            'evento' => 'usuario_exclusao',
            'id' => 777
        ];

        $this->post('/webhooks/index', $data);

        $this->assertResponseOk();

        $deletedUser = $users->find()
            ->where([
                'usuario_assinante_id' => 777
            ])
            ->first();

        $this->assertEmpty($deletedUser);
    }
}