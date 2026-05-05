<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use ArrayObject;
use Cake\Log\Log;
use Cake\Http\Client;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->hasMany('Sessoes');
        $this->hasMany('Apostilas');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'O email é obrigatório')
            ->add('email', 'NotGmail', [
                'rule' => ['custom', '/@(?!gmail\.com$).+/i'],
                'message' => '@gmail.com não é permitido'
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255, 'Máximo 255 caracteres')
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'A senha é obrigatória')
            ->add('password', 'letraNumero', [
                'rule' => ['custom', '/^(?=.*[A-Za-z])(?=.*\d).+$/'],
                'message' => 'A senha precisa ter ao menos 1 letra e 1 número'
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    public function afterSave(EventInterface $event, $entity, ArrayObject $options)
    {
        try {
            if (!empty($options['fromWebhook'])) {
                return;
            }
            
            if($entity->isNew()) {
            
                if(!empty($entity->assinante_id) || !empty($entity->usuario_assinante_id)) {
                    return;
                }

                $assinanteId = $this->cadastrarAssinante($entity);

                if(!$assinanteId) {
                    Log::error('Erro: assinante não criado');
                    return;
                }

                $usuarioAssinanteId = $this->cadastrarUsuarioAssinante($entity);

                if(!$usuarioAssinanteId) {
                    Log::error('Erro: usuario_assinante não criado');
                    return;
                }
            }

            if(!$entity->isDirty('plain_password')) {
                return;
            }

            if(empty($entity->usuario_assinante_id)) {
                return;
            }

            $this->alterarSenha($entity);

        } catch(\Exception $e) {
            Log::error('Erro integração onboarding: ' . $e->getMessage());
        }
    }

    public function cadastrarAssinante($entity)
    {
        $http = new Client();

        $data = [
            'codigo' => $entity->email,
            'nome' => $entity->email
        ];

        $url = "http://localhost/onboarding/v1/2a5d7400f3b1d2876bee4938d89d9e24/api-assinantes.json";

        $response = $http->post($url, $data, [
            'type' => 'json',
        ]);

        if(!$response->isOk()) {
            Log::error('Erro ao criar assinante: ' . $response->getStatusCode());
            return null;
        }

        $id = $response->getJson();
        $assinanteId = $id['id'];

        $entity->assinante_id = $assinanteId;

        $this->save($entity, [
            'callbacks' => false,
            'checkRules' => false
        ]);

        return $assinanteId;
    }

    public function cadastrarUsuarioAssinante($entity)
    {
        $http = new Client();

        $data = [
            'login' => $entity->email,
            'nome'  => $entity->email,
            'senha' => $entity->plain_password,
            'email' => $entity->email
        ];

        $url = "http://localhost/onboarding/v1/2a5d7400f3b1d2876bee4938d89d9e24/api-usuario-assinantes.json";

        $response = $http->post($url, $data, [
            'type' => 'json'
        ]);
        
        if(!$response->isOk()) {
            Log::error('Erro ao criar usuario_assinante: ' . $response->getStatusCode());
            return null;
        }

        $id = $response->getJson();

        if(empty($id['id'])) {
            Log::error('Resposta sem o ID do usuario_assinante');
            return null;
        }

        $usuarioAssinanteId = $id['id'];

        $entity->usuario_assinante_id = $usuarioAssinanteId;
        $entity->setDirty('usuario_assinante_id', true);

        $this->save($entity, [
            'callbacks' => false,
            'checkRules' => false
        ]);

        return $usuarioAssinanteId;
    }

    public function alterarSenha($entity) 
    {
        $http = new Client();

        $data = [
            'senha' => $entity->plain_password
        ];

        $id = $entity->usuario_assinante_id;

        $url = "http://localhost/onboarding/v1/2a5d7400f3b1d2876bee4938d89d9e24/api-usuarios-assinantes/{$id}.json";

        $response = $http->post($url, $data, [
            'type' => 'json'
        ]);

        if(!$response->isOk()) {
            Log::error('Erro ao alterar senha: ' . $response->getStatusCode());
            return null;
        }

        return true;
    }
}
