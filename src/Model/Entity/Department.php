<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * Department Entity.
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\User[] $users
 */
class Department extends Entity
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
    public function countNumberEmp($department_id)
    {
        return count(TableRegistry::get('DepartmentsUsers')->find()->where(['department_id' => $department_id])->toArray());
    }
    public function listManager($department_id)
    {
        $connection = ConnectionManager::get('default');
        $query = "SELECT u.`username` FROM users AS u INNER JOIN departments_users AS du ON
u.`id` = du.`user_id` WHERE du.`department_id`  = $department_id AND du.`manager` = TRUE";
        $arrayManager = $connection
        ->execute($query)
        ->fetchAll('assoc');
        if (empty($arrayManager)) {
            return '';
        } else {
            foreach ($arrayManager as $key => $value) {
                $usernames[] = $value['username'];
            };
            return $listManager= implode(", ", $usernames);
        }
    }
}
