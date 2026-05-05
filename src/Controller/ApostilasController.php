<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Apostilas Controller
 *
 * @property \App\Model\Table\ApostilasTable $Apostilas
 * @method \App\Model\Entity\Apostila[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ApostilasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();

        $this->viewBuilder()->setLayout('MetronicV4.demo5');
        
        $userId = $this->request->getAttribute('identity')->getIdentifier();

        $query = $this->Apostilas->find()
            ->where(['Apostilas.user_id' => $userId]);
        
        $apostilas = $this->paginate($query);
        $this->set(compact('apostilas'));
    }

    /**
     * View method
     *
     * @param string|null $id Apostila id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $apostila = $this->Apostilas->get($id, [
            'contain' => ['Users', 'Sessoes'],
        ]);

        $this->Authorization->authorize($apostila);

        $this->set(compact('apostila'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $apostila = $this->Apostilas->newEmptyEntity();

        $this->Authorization->authorize($apostila);

        if ($this->request->is('post')) {

            $apostila = $this->Apostilas->patchEntity($apostila, $this->request->getData());

            $apostila->user_id = $this->request->getAttribute('identity')->getIdentifier();

            $arquivo = $this->request->getSession()->read('Upload.apostila');

            if (!empty($arquivo)) {
                $apostila->arquivo = $arquivo;
            }

            if ($this->Apostilas->save($apostila)) {

                $this->request->getSession()->delete('Upload.apostila');

                $this->Flash->success(__('Apostila salva com sucesso!'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Erro ao salvar apostila.'));
        }

        $this->set(compact('apostila'));
    }

    public function upload()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $file = $this->request->getData('file');

        if (!$file || $file->getError() !== 0) {
            throw new \Exception('Erro no upload');
        }

        $nomeArquivo = uniqid() . '_' . $file->getClientFilename();

        $destino = WWW_ROOT . 'uploads' . DS . 'apostilas' . DS . $nomeArquivo;

        $file->moveTo($destino);

        $this->request->getSession()->write('Upload.apostila', $nomeArquivo);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'ok']));
    }

    /**
     * Edit method
     *
     * @param string|null $id Apostila id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $apostila = $this->Apostilas->get($id);

        $this->Authorization->authorize($apostila);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $apostila = $this->Apostilas->patchEntity($apostila, $this->request->getData());

            $arquivo = $this->request->getSession()->read('Upload.apostila');

            if (!empty($arquivo)) {
                $apostila->arquivo = $arquivo;

                $this->request->getSession()->delete('Upload.apostila');
            }

            if ($this->Apostilas->save($apostila)) {
                $this->Flash->success(__('Apostila atualizada com sucesso!'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Erro ao atualizar apostila.'));
        }

        $this->set(compact('apostila'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Apostila id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $apostila = $this->Apostilas->get($id);

        $this->Authorization->authorize($apostila);

        if ($this->Apostilas->delete($apostila)) {
            $this->Flash->success(__('Apostila deletada!'));
        } else {
            $this->Flash->error(__('Erro ao deletar apostila.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
