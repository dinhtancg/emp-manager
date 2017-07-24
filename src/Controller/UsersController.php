<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Mailer\Email;

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
     public function me()
     {
         $user = $this->Users->get($this->Auth->user('id'), [
           'contain' => ['Departments']
       ]);

         $this->set('user', $user);
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
         $user = $this->Users->get($id, [
             'contain' => ['Departments']
         ]);

         $this->set('user', $user);
         $this->set('_serialize', ['user']);
     }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->session()->read('Auth.User.role') != 1) {
            // 1 is Admin
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
                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($user->uploadAvatar($this->request->data['base64-avatar'], $this->request->data['avatar'])) {
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('The user has been saved.'));
                        return $this->redirect(['action' => 'me']);
                    } else {
                        $this->Flash->error(__('The user could not be saved. Please, try again.'));
                    }
                } else {
                    $this->Flash->error(__('The avatar could not be saved. please try again.'));
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
        if ($id == $this->request->session()->read('Auth.User.id') && $this->request->session()->read('Auth.User.role') != 1) {
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
                $this->Auth->setUser($user);
                $this->Auth->user->login_fail = null;
                $this->Auth->user->time_ban = null;
                $this->Users->save($this->Auth->user());
                if ($this->Auth->user('first_login') == 0) {
                    // 0 : The user has never logged in before
                    return $this->redirect(['controller' => 'users', 'action'=>'changePassword']);
                } elseif ($this->Auth->user('role')) {
                    return $this->redirect(['prefix'=>'admin','controller' => 'users', 'action' => 'index']);
                } else {
                    return $this->redirect($this->Auth->redirectUrl());
                }
            } else {
                $userF = $this->Users->findByUsername($this->request->data['username'])->first();
                if (time() - $userF->time_ban < 0) {
                    return $this->Flash->error(__('Your account is locked '));
                }
                if ($userF->login_fail >= 5) {
                    return $this->Flash->error(__('Your account has block 10 minutes!'));
                    $time_ban  = time()+ 600;
                    $time=Time::now()->mod;
                    $this->Users->updateAll(['time_ban' => $time_ban], ['id' => $userF->id]);
                } else {
                    $userF->login_fail = $userF->login_fail + 1;
                    $this->Users->save($userF);
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
              'first_login' => 'true'
            ],
            ['validate' => 'password']
            );
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The password is successfully changed'));

                if ($user['role'] === 'admin') {
                    $this->redirect('/admin/users/index');
                } else {
                    $this->redirect('/users/index');
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
}
