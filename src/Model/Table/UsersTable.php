<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Departments
 */
class UsersTable extends Table
{
    use SoftDeleteTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('username');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Departments', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'department_id',
            'joinTable' => 'departments_users'
        ]);
    }

        /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->add('username', [
                'length' => [
                    'rule' => ['minLength', 5],
                    'message' => 'The username have to be at least 5 characters!',
                ]
            ]);

        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email');
        $validator
            ->requirePresence('full_name', 'create')
            ->notEmpty('full_name')
            ->add('full_name', [
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => 'The fullname have to be at least 3 characters!',
                ]
            ]);

        $validator
                ->requirePresence('password', 'create')
                ->notEmpty('password', 'You must enter a password', 'create')
            ->add('password', [
                'length' => [
                'rule' => ['minLength', 8],
                'message' => 'Passwords must be at least 8 characters long.',
            ]
            ])
                ->add('password', 'custom', [
                    'rule' => [$this, 'checkCharacters'],
                    'message' => 'The password must contain at least 1 number, 1 uppercase, 1 lowercase, and 1 special character'
                ]);
        $validator
              ->requirePresence('birthday', 'create')
              ->notEmpty('birthday', 'You must enter your birthday', 'create')
              ->add('birthday', 'custom', [
                'rule' => function ($value) {
                    return checkdate($value['month'], $value['day'], $value['year']);
                },
                'message' => 'The date of birth is invalid!'
              ]);
        $validator
                    ->requirePresence('avatar', 'create')
                    ->notEmpty('avatar', 'You must choose your avatar!', 'create')
                    ->add('avatar', [
                      'validExtension' => [
                          'rule' => ['extension',['gif', 'jpeg', 'png', 'jpg']],
                          'message' => 'These files extension are allowed: gif, jpeg, png, jpg',
                      ]
    ]);
        $validator
            ->requirePresence('role', 'create')
            ->notEmpty('role')
            ->add('role', 'inList', [
              'rule'=> ['inList', ['0','1']],
              'message' => 'Please enter a valid role'
            ]);
        $validator
            ->requirePresence('gender', 'create')
            ->notEmpty('gender')
            ->add('gender', 'inList', [
              'rule'=> ['inList', ['men','women','other']],
              'message' => 'Please enter a valid gender.'
            ]);
        $validator
            ->requirePresence('first_login', 'create')
            ->notEmpty('first_login');

        $validator
            ->requirePresence('confirm_password', 'create')
            ->notEmpty('confirm_password')
            ->allowEmpty('confirm_password', 'update')
            ->add('confirm_password', 'compareWith', [
                'rule' => ['compareWith', 'password'],
                'message' => 'Passwords do not match.'
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
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
    public function validationPassword(Validator $validator)
    {
        $validator
            ->add('old_password', 'custom', [
                'rule'=>  function ($value, $context) {
                    $user = $this->get($context['data']['id']);
                    if ($user) {
                        if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                            return true;
                        }
                    }
                    return false;
                },
                'message'=>'The old password does not match the current password!',
            ])
            ->notEmpty('old_password');

        $validator
               ->requirePresence('password1', 'create')
               ->notEmpty('password1')
                ->add('password1', [
                    'length' => [
                    'rule' => ['minLength', 8],
                    'message' => 'Passwords must be at least 8 characters long.',
                ]
                ])
                ->add('password2', 'compareWith', [
                    'rule' => ['compareWith', 'password1'],
                    'message' => 'Passwords do not match.'
                ])

               ->add('password1', 'custom', [
                   'rule' => [$this, 'checkCharacters'],
                   'message' => 'The password must contain at least 1 number, 1 uppercase, 1 lowercase, and 1 special character'
               ]);
        $validator
                  ->requirePresence('password2', 'create')
                  ->notEmpty('password2')
                      ->add('password2', [
                          'length' => [
                          'rule' => ['minLength', 8],
                          'message' => 'Passwords must be at least 8 characters long.',
                      ]
                    ])
                      ->add('password2', 'compareWith', [
                          'rule' => ['compareWith', 'password1'],
                          'message' => 'Passwords do not match.'
                      ])

                  ->add('password', 'custom', [
                      'rule' => [$this, 'checkCharacters'],
                      'message' => 'The password must contain at least 1 number, 1 uppercase, 1 lowercase, and 1 special character'
                  ]);

        return $validator;
    }
    /**
     * Checks password for a single instance of each:
     * number, uppercase, lowercase, and special character
     *
     * @param type $password
     * @param array $context
     * @return boolean
     */
    public function checkCharacters($password, array $context)
    {
        // number
        if (!preg_match("#[0-9]#", $password)) {
            return false;
        }
        // Uppercase
        if (!preg_match("#[A-Z]#", $password)) {
            return false;
        }
        // lowercase
        if (!preg_match("#[a-z]#", $password)) {
            return false;
        }
        // special characters
        if (!preg_match("#\W+#", $password)) {
            return false;
        }
        return true;
    }
}
