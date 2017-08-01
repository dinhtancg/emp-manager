<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\Time $birthday
 * @property string $avatar
 * @property bool $role
 * @property bool $first_login
 * @property bool $login_failse
 * @property \Cake\I18n\Time $time_ban
 * @property string $pass_key
 * @property \Cake\I18n\Time $timeout
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property bool $flag_delete
 * @property \App\Model\Entity\Department[] $departments
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * Hash password
     * @param type $password
     * @return type hash password
     */
    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
    /**
     * Upload image avatar check
     * @param type $avatar
     * @param type $fileName
     * @return boolean
     */
    public function uploadAvatar($avatar, $fileName)
    {
        $avatar = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
        if (file_put_contents(UPLOAD_DIR.$fileName, $avatar)) {
            return true;
        }
    }
    /**
     * [isManager check manager method]
     * @param  [type]  $user_id       [description]
     * @param  [type]  $department_id [description]
     * @return boolean                [description]
     */
    public function isManager($user_id, $department_id)
    {
        $query = TableRegistry::get('DepartmentsUsers')
          ->find('all', ['fields'=>['manager']])
          ->where(['user_id' => $user_id, 'department_id' => $department_id])->toArray();
        if (count($query) ==0) {
            return false;
        }
        return (bool)$query[0]->manager;
    }
    public function managerOf($user_id)
    {
        $managers = TableRegistry::get('DepartmentsUsers')
        ->find('all', ['fields'=>['department_id']])
        ->where(['user_id' => $user_id, 'manager' => true])->toArray();
        $listId = [];
        foreach ($managers as $manager) {
            array_push($listId, $manager->department_id);
        }
        return $listId;
    }
}
