<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\Utility\Text;

class WebhooksController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Users');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['index']);
        $this->Authorization->skipAuthorization();
    }

    public function index()
    {
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();

        $evento = $data['evento'] ?? null;

        if (!$evento) {
            return;
        }

        if ($evento === 'usuario_criacao') {
            $this->usuarioCriacao($data);
        }
        elseif ($evento === 'usuario_alteracao') {
            $this->usuarioAlteracao($data);
        }
        elseif ($evento === 'usuario_exclusao') {
            $this->usuarioExclusao($data);
        }

        $this->autoRender = false;

        $this->response = $this->response->withStatus(200);
    }

    public function usuarioCriacao($data)
    {
        $password = 'Temp1234';

        $user = $this->Users->newEntity([
            'email' => $data['email'],
            'password' => $password,
            'usuario_assinante_id' => $data['id'],
            'assinante_id' => $data['assinantes'][0]['id']
        ]);

        $this->Users->save($user, [
            'fromWebhook' => true
        ]);
    }

    public function usuarioAlteracao($data)
    {
        $user = $this->Users
            ->find()
            ->where([
                'usuario_assinante_id' => $data['id']
            ])
            ->first();

        if (!$user) {
            Log::debug('Usuário não encontrado para alteração');

            return;
        }

        $user->email = $data['email'];

        if (!empty($data['senha'])) {
            $user->password = $data['senha'];
        }

        if (!$this->Users->save($user, [
            'fromWebhook' => true
        ])) {

            Log::debug(json_encode($user->getErrors()));
        }
    }

    public function usuarioExclusao($data)
    {
        $user = $this->Users
            ->find()
            ->where([
                'usuario_assinante_id' => $data['id']
            ])
            ->first();

        if (!$user) {
            Log::debug('Usuário não encontrado para exclusão');

            return;
        }

        if (!$this->Users->delete($user)) {
            Log::debug('Erro ao excluir usuário');
        }
    }
}