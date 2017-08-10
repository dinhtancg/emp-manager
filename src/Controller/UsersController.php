<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
     public function index()
     {
         $sessionLimit = $this->request->session()->read('users.index.limit');
         $limit = $sessionLimit ? $this->request->session()->read('users.index.limit') : LIMIT_PAGINATE;
         if ($this->request->is('get')) {
             if (array_key_exists('limit', $this->request->query) && in_array($this->request->query['limit'], [10,20,50])) {
                 $limit = $this->request->query['limit'];
                 $this->request->session()->write('users.index.limit', $limit);
                 $sessionLimit = $this->request->session()->read('users.index.limit');
             }
         }
         $user = $this->Users->get($this->Auth->user('id'));
         $departments = $this->Users->Departments->find()->matching('Users', function ($q) use ($user) {
             return $q->where(['Users.id' => $user->id]);
         });
         $this->set('user', $user);
         $this->set('sessionLimit', $sessionLimit);
         $this->set('departments', $this->Paginator->paginate($departments, ['limit'=> $limit]));
         $this->set('_serialize', ['user']);
     }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function view($id = null)
     {
         $sessionLimit = $this->request->session()->read('users.view.limit');
         $limit = $sessionLimit ? $this->request->session()->read('users.view.limit') : LIMIT_PAGINATE;
         if ($this->request->is('get')) {
             if (array_key_exists('limit', $this->request->query) && in_array($this->request->query['limit'], [10,20,50])) {
                 $limit = $this->request->query['limit'];
                 $this->request->session()->write('users.view.limit', $limit);
                 $sessionLimit = $this->request->session()->read('users.view.limit');
             }
         }
         $user = TableRegistry::get('Users')->find()->where(['id'=>$id])->first();
         if (!$user) {
             $this->Flash->error(__('User not found!'));
             $this->redirect(['controller'=> 'users', 'action'=> 'index']);
         } else {
             $departments = $this->Users->Departments->find()->matching('Users', function ($q) use ($user) {
                 return $q->where(['Users.id' => $user->id]);
             });
             $loggedUser = TableRegistry::get('Users')->get($this->request->session()->read('Auth.User.id'));
             $checkDepartments = $departments->toArray();

             $isManager = false;

             for ($run=0; $run < count($checkDepartments) ; $run++) {
                 if (in_array($checkDepartments[$run]->id, $loggedUser->managerOf($loggedUser->id))) {
                     $isManager = true;
                     break;
                 }
             }

             $this->set('isManager', $isManager);
             $this->set('sessionLimit', $sessionLimit);
             $this->set('user', $user);
             $this->set('departments', $this->Paginator->paginate($departments, ['limit'=> $limit]));
             $this->set('_serialize', ['user']);
         }
     }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->session()->read('Auth.User.role') != true) {
            $this->Flash->error(__('Permission denied'));
            $this->redirect(['controller'=> 'users', 'action'=> 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id != $this->request->session()->read('Auth.User.id')) {
            $this->Flash->error(__('You can not edit user!'));
            $this->redirect(['controller'=> 'users', 'action'=> 'view',$id]);
        } else {
            $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['Departments']
        ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                if ($this->request->data['base64-avatar'] != '' && $this->request->data['avatar']!= '') {
                    $this->request->data['avatar'] = $user->username.$this->request->data['avatar'];
                    $user = $this->Users->patchEntity($user, $this->request->data);
                    if ($user->uploadAvatar($this->request->data['base64-avatar'], $this->request->data['avatar'])) {
                        if ($this->Users->save($user)) {
                            $this->Flash->success(__('The user has been saved.'));
                            return $this->redirect(['action' => 'index']);
                        } else {
                            $this->Flash->error(__('The user could not be saved. Please, try again.'));
                        }
                    } else {
                        $this->Flash->error(__('The avatar could not be saved. please try again.'));
                    }
                } else {
                    unset($this->request->data['base64-avatar']);
                    $this->request->data['avatar'] = $user->avatar;
                    $user = $this->Users->patchEntity($user, $this->request->data);
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('The user has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The user could not be saved. Please, try again.'));
                    }
                }
            }
            $departments = $this->Users->Departments->find('list', ['limit' => QUERY_LIMIT]);
            $this->set(compact('user', 'departments'));
            $this->set('_serialize', ['user']);
        }
    }

    /**
     * Delete method
     * @param  [type] $id [description]
     * @return [type]    user can not delete user
     */
    public function delete($id = null)
    {
        if ($id == $this->request->session()->read('Auth.User.id') && $this->request->session()->read('Auth.User.role') != true) {
            $this->Flash->error(__('Permission denied'));
            $this->redirect(['controller'=> 'users', 'action'=> 'view',$id]);
        }
        if ($id != $this->request->session()->read('Auth.User.id')) {
            $this->Flash->error(__('You can not delete user!'));
            $this->redirect(['controller'=> 'users', 'action'=> 'view',$id]);
        }
    }

    /**
     * Login method
     *
     * @return \Cake\Network\Response|null
     */
    public function login()
    {
        if ($this->request->session()->read('Auth.User.id')) {
            $this->Flash->success(__('Logined!!'));
            $this->redirect('/users');
        }
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                //clean login fail information
                // 0 is default value of login_fail
//                $user = new \Cake\ORM\Entity()
                $userS = $this->Users->findById($user['id'])->first();
                $userS->login_fail = 0;
                $userS->time_ban = null;
                $this->Users->save($userS);
                $this->Auth->setUser($user);

                //check first login
                if ($this->Auth->user('first_login') == false) {
                    return $this->redirect(['controller' => 'users', 'action'=>'changePassword']);
                } elseif ($this->Auth->user('role')) {
                    return $this->redirect(['prefix'=>'admin','controller' => 'Users', 'action' => 'index']);
                } else {
                    return $this->redirect(['prefix'=>false,'controller' => 'Departments', 'action' => 'index']);
                }
            } else {
                //find user by username
                $userF = $this->Users->findByUsername($this->request->data['username'])->first();
                //check time_ban; login_fail count
                if ($userF) {
                    if ($userF->time_ban && (time() - $userF->time_ban->toUnixString()) < 0) {
                        return $this->Flash->error(__('Your account is locked, please login late!'));
                    } elseif ($userF->login_fail == MAX_LOGIN_FAIL) {
                        $userF->time_ban = time()+ TIME_BAN ;
                        $this->Users->save($userF);
                        return $this->Flash->error(__('Your account has block 10 minutes!'));
                    } else {
                        $userF->login_fail ++;
                        $this->Users->save($userF);
                    }
                } else {
                    return $this->Flash->error(__('Can not found your username!'));
                }
            }
            $this->Flash->error(__('Invalid username or password, please try again!'));
        }
    }
    /**
     * Logout method
     *
     * @return \Cake\Network\Response|null
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    /**
      * [changePassword method]
      * @return [type] [description]
      */
    public function changePassword()
    {
        $user =$this->Users->get($this->Auth->user('id'));
        if ($this->request->data) {
            $user = $this->Users->patchEntity($user, [
              'old_password' => $this->request->data['old_password'],
              'password' => $this->request->data['password1'],
              'password1' => $this->request->data['password1'],
              'password2' => $this->request->data['password2'],
              'first_login' => true
            ],
            ['validate' => 'password']
            );
            if ($this->Users->save($user)) {
                $this->request->session()->write('Auth.User.first_login', true);
                $this->Flash->success(__('The password is successfully changed'));

                if ($user['role'] == true) {
                    $this->redirect('/admin/users');
                } else {
                    $this->redirect('/users');
                }
            } else {
                $this->Flash->error(__('There was an error during the save!'));
            }
        }
        $this->set('user', $user);
    }

    /**
     * Implement process reset password
     * @return [type] [description]
     */
    public function password()
    {
        if ($this->request->is('post')) {
            $query = $this->Users->findByEmail($this->request->data['email']);
            $user = $query->first();
            if (!$user) {
                $this->Flash->error(__('Email address does not exist. Please try again!'));
            } else {
                $pass_key = uniqid();
                $url = Router::Url(['controller' =>'users', 'action' => 'reset'], true).'/'. $pass_key;
                $timeout = time()+ DAY;
                if ($this->Users->updateAll(['pass_key' => $pass_key, 'timeout' => $timeout], ['id' => $user->id])) {
                    $this->sendResetEmail($url, $user);
                } else {
                    $this->Flash->error('Error saving reset pass_key/ timeout');
                }
            }
        }
    }
    public function resetSuccess()
    {
        # code...
    }
}
