<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public $fields = [
        'id' => ['type' => 'integer'],
        'email' => ['type' => 'string', 'length' => 255],
        'password' => ['type' => 'string', 'length' => 255],

        'assinante_id' => [
            'type' => 'integer',
            'null' => true,
            'default' => null,
        ],

        'usuario_assinante_id' => [
            'type' => 'integer',
            'null' => true,
            'default' => null,
        ],

        'created' => 'datetime',

        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ];

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'test1@mail.com',
                'password' => '123Abc',
                'assinante_id' => null,
                'usuario_assinante_id' => null,
                'created' => '2026-01-15 16:56:45',
            ],
            [
                'id' => 2,
                'email' => 'test2@email.com',
                'password' => '1234Ab',
                'assinante_id' => null,
                'usuario_assinante_id' => null,
                'created' => '2026-02-17 11:56:45',
            ],
        ];

        parent::init();
    }
}