<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sessoes Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ApostilasTable&\Cake\ORM\Association\BelongsTo $Apostilas
 *
 * @method \App\Model\Entity\Sesso newEmptyEntity()
 * @method \App\Model\Entity\Sesso newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Sesso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sesso get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sesso findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Sesso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sesso[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sesso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sesso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sesso[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sesso[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sesso[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sesso[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SessoesTable extends Table
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

        $this->setTable('sessoes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Apostilas', [
            'foreignKey' => 'apostila_id',
        ]);
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
            ->nonNegativeInteger('user_id')
            ->notEmptyString('user_id');

        $validator
            ->nonNegativeInteger('apostila_id')
            ->allowEmptyString('apostila_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name', 'O nome da sessão é obrigatório');

        $validator
            ->date('sessao_date')
            ->allowEmptyDate('sessao_date');

        $validator
            ->time('start_time')
            ->allowEmptyTime('start_time')
            ->add('start_time', 'HoraInicial18', [
                'rule' => function($value, $context) {

                    if (strtotime($value) < strtotime("18:00")) {
                        return true;
                    }

                    return 'Hora inicial não pode ser maior que 18:00';
                }
            ]);

        $validator
            ->time('end_time')
            ->allowEmptyTime('end_time')
            ->add('end_time', 'HoraFinalMenor', [
                'rule' => function ($value, $context) {

                    if (strtotime($value) > strtotime($context['data']['start_time'])) {
                        return true;
                    }

                    return 'Hora final não pode ser menor que a hora inicial';
                }
            ]);

        $validator
            ->scalar('conteudo')
            ->allowEmptyString('conteudo');

        $validator
            ->scalar('objetivo')
            ->allowEmptyString('objetivo');

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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn('apostila_id', 'Apostilas'), ['errorField' => 'apostila_id']);
        $rules->add(function ($entity, $options) {

            $ultimaSessao = $this->find()
                ->where(['user_id' => $entity->user_id])
                ->order(['created' => 'DESC'])
                ->first();

            if (!$ultimaSessao) {
                return true;
            }

            if($entity->sessao_date > $ultimaSessao->sessao_date) {
                return true;
            }

            if ($entity->start_time > $ultimaSessao->end_time) {
                return true;
            }

            return false;
        }, 'SessaoAposOutra', [
            'errorField' => 'start_time',
            'message' => 'Hora ou data deve ser maior que a da última sessão criada'
        ]);

        return $rules;
    }
}
