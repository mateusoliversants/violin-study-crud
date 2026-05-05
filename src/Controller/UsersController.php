<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'post']);
        
        $this->viewBuilder()->setLayout('MetronicV4.login');

        $result = $this->Authentication->getResult();

        if($result->isValid()) {
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Sessoes',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }

        if($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Email ou senha inválidos');
        }
    }

    public function logout()
    {
        $this->Authorization->skipAuthorization();

        $this->Authentication->logout();
        return $this->redirect(['action' => 'login']);
    }

    public function register()
    {
        $this->Authorization->skipAuthorization();

        $this->viewBuilder()
            ->setLayout('MetronicV4.login')
            ->setTemplate('register');

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $user = $this->Users->patchEntity($user, $data);

            $user->plain_password = $data['password'];

            if ($this->Users->save($user)) {
                $this->Flash->success('Usuário cadastrado!');
                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error('Erro ao criar usuário');
        }

        $this->set(compact('user'));
    }

    public function forgotPassword()
    {
        $this->Authorization->skipAuthorization();

        $this->viewBuilder()
            ->setLayout('MetronicV4.login')
            ->setTemplate('forgot_password');

        if($this->request->is('post')) {
            $email = $this->request->getData('email');

            $user = $this->Users->find()
                ->where(['email' => $email])
                ->first();
            
            if ($user) {
                $user->password = $this->request->getData('new_password');

                if ($this->Users->save($user)) {
                    $this->Flash->success('Senha redefinida!');
                    return $this->redirect(['action' => 'login']);
                }
            }

            $this->Flash->error('Email não encontrado');
        }
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions([
            'login',
            'register',
            'forgotPassword'
        ]);
    }
}
