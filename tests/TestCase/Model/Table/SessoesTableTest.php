<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SessoesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SessoesTable Test Case
 */
class SessoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SessoesTable
     */
    protected $Sessoes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Sessoes',
        'app.Users',
        'app.Apostilas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Sessoes') ? [] : ['className' => SessoesTable::class];
        $this->Sessoes = $this->getTableLocator()->get('Sessoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Sessoes);

        parent::tearDown();
    }

    public function testSaveSessao(): void
    {
        $sessaoCriada = [
            'user_id' => 1,
            'apostila_id' => 1,
            'name' => 'Sessão 2222',
            'created' => '2026-01-15 20:25:15',
            'sessao_date' => '2026-02-17',
            'start_time' => '11:00:00',
            'end_time' => '14:30:00',
            'conteudo' => 'Conteúdo 222',
            'objetivo' => 'Objetivo 222',
        ];

        $sessao = $this->Sessoes->newEntity($sessaoCriada);
        $result = $this->Sessoes->save($sessao);

        $this->assertNotFalse($result);
    }

    public function testUserExisteSessao(): void
    {
        $sessaoCriada = [
            'user_id' => 70,
            'apostila_id' => 1,
            'name' => 'Sessão 2222',
            'created' => '2026-01-15 20:25:15',
            'sessao_date' => '2026-02-17',
            'start_time' => '11:00:00',
            'end_time' => '14:30:00',
            'conteudo' => 'Conteúdo 222',
            'objetivo' => 'Objetivo 222',
        ];

        $sessao = $this->Sessoes->newEntity($sessaoCriada);
        $result = $this->Sessoes->save($sessao);

        $this->assertFalse($result);
    }

    public function testHoraFinalMenor(): void
    {
        $sessaoCriada = [
            'user_id' => 1,
            'apostila_id' => 1,
            'name' => 'Sessão hora final menor',
            'created' => '2026-02-17 10:42:34',
            'sessao_date' => '2026-02-17',
            'start_time' => '13:00:00',
            'end_time' => '11:30:00',
            'conteudo' => '123',
            'objetivo' => 'abc',
        ];

        $sessao = $this->Sessoes->newEntity($sessaoCriada);

        dd($sessao->getErrors());
        $this->assertNotEmpty($sessao->getErrors());
    }
}
