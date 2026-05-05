<?php
declare(strict_types=1);

namespace App\Controller;

use Authorization\Exception\ForbiddenException;
use Cake\I18n\FrozenDate;

/**
 * Sessoes Controller
 *
 * @property \App\Model\Table\SessoesTable $Sessoes
 * @method \App\Model\Entity\Sesso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SessoesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->authorize($this->Sessoes);

        $this->viewBuilder()->setLayout('MetronicV4.demo5');

        $userId = $this->request->getAttribute('identity')->getIdentifier();

        $query = $this->Sessoes->find()
            ->where(['Sessoes.user_id' => $userId])
            ->contain(['Users', 'Apostilas']);
        
        $sessoes = $this->paginate($query);

        $this->set(compact('sessoes'));
    }

    /**
     * View method
     *
     * @param string|null $id Sessao id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sesso = $this->Sessoes->get($id, [
            'contain' => ['Users', 'Apostilas'],
        ]);

        $this->Authorization->authorize($sesso);

        $this->set(compact('sesso'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sesso = $this->Sessoes->newEmptyEntity();

        $this->Authorization->authorize($sesso);

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            if (!empty($data['sessao_date'])) {
                $data['sessao_date'] = FrozenDate::createFromFormat(
                    'd/m/Y',
                    $data['sessao_date']
                );
            }

            $sesso = $this->Sessoes->patchEntity($sesso, $data);

            $sesso->user_id = $this->request->getAttribute('identity')->getIdentifier();

            if ($this->Sessoes->save($sesso)) {
                $this->Flash->success(__('Sessão salva com sucesso!'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Erro ao salvar sessão.'));
        }

        $userId = $this->request->getAttribute('identity')->getIdentifier();

        $apostilas = $this->Sessoes->Apostilas->find('list')
            ->where(['Apostilas.user_id' => $userId])
            ->all();

        $this->set(compact('sesso', 'apostilas'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sessao id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sesso = $this->Sessoes->get($id);

        if (!empty($sesso->sessao_date)) {
            $sesso->sessao_date = $sesso->sessao_date->format('d/m/Y');
        }

        $this->Authorization->authorize($sesso);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            if (!empty($data['sessao_date'])) {
                $data['sessao_date'] = FrozenDate::createFromFormat(
                    'd/m/Y',
                    $data['sessao_date']
                );
            }

            $sesso = $this->Sessoes->patchEntity($sesso, $data);

            if ($this->Sessoes->save($sesso)) {
                $this->Flash->success(__('Sessão atualizada com sucesso!'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Erro ao atualizar sessão.'));
        }

        $userId = $this->request->getAttribute('identity')->getIdentifier();

        $apostilas = $this->Sessoes->Apostilas->find('list')
            ->where(['Apostilas.user_id' => $userId])
            ->all();

        $this->set(compact('sesso', 'apostilas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sessao id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $sesso = $this->Sessoes->get($id);

        $this->Authorization->authorize($sesso);

        if ($this->Sessoes->delete($sesso)) {
            $this->Flash->success(__('Sessão deletada!'));
        } else {
            $this->Flash->error(__('Erro ao deletar sessão.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
