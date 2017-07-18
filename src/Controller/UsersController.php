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
                if ($this->Auth->user('first_login') == 0) {
                    return $this->redirect(['controller' => 'users', 'action'=>'changePassword']);
                } elseif ($this->Auth->user('role')) {
                    return $this->redirect(['prefix'=>'admin','controller' => 'users', 'action' => 'index']);
                } else {
                    return $this->redirect($this->Auth->redirectUrl());
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
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $user =$this->Users->get($this->Auth->user('id'));
        $user = $this->Users->get($this->Auth->user('id'), [
          'contain' => ['Departments']
      ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }


     /**
      * View user information
      * @param  [type] $id [description]
      * @return [type]     [description]
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
    * Edit user information method
    * @param  [type] $id [description]
    * @return [type]     [description]
    */
    public function edit($id = null)
    {
        if ($id != $this->request->session()->read('Auth.User.id')) {
            $this->Flash->error(__('You can not edit profile of this user!'));
            $this->redirect(['controller'=> 'users', 'action'=> 'view',$id]);
        } else {
            $user = $this->Users->get($id, [
              'contain' => []
          ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                }
            }
            $this->set(compact('user'));
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
        if ($id == $this->request->session()->read('Auth.User.id') && $this->request->session()->read('Auth.User.role') != 'admin') {
            $this->Flash->error(__('Permission denied'));
            $this->redirect(['controller'=> 'users', 'action'=> 'view',$id]);
        }
        if ($id != $this->request->session()->read('Auth.User.id')) {
            $this->Flash->error(__('You can not edit profile of this user!'));
            $this->redirect(['controller'=> 'users', 'action'=> 'view',$id]);
        }
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
                $passkey = uniqid();
                $url = Router::Url(['controller' =>'users', 'action' => 'reset'], true).'/'. $passkey;
                $timeout = time()+ DAY;
                if ($this->Users->updateAll(['passkey' => $passkey, 'timeout' => $timeout], ['id' => $user->id])) {
                    $this->sendResetEmail($url, $user);
                } else {
                    $this->Flash->error('Error saving reset passkey/ timeout');
                }
            }
        }
    }

    /**
     * Send email to user for reset password
     * @param string $url  Link reset password
     * @param  $user
     */
    private function sendResetEmail($url, $user)
    {
        $email = new Email();
        $email-> template('resetpw');
        $email->emailFormat('both');
        $email->from('tanhd070695@gmail.com');
        $email->to($user->email, $user->username);
        $email->subject('Reset your password');
        $email->viewVars(['url'=>$url, 'username'=> $user->username]);
        if ($email->send()) {
            $this->Flash->success(__('Check your email for your password reset link!'));
        } else {
            $this->Flash->error(__('Error sending email :'). $email->smtpError);
        }
    }
    /**
     * [reset password medthod]
     * @param [type] $passkey [description]
     */
    public function reset($passkey = null)
    {
        if ($passkey) {
            $query = $this->Users->find('all', ['conditions' => ['passkey' => $passkey, 'timeout >' =>time()]]);
            $user = $query->first();
            if ($user) {
                if (!empty($this->request->data)) {
                    //Clear passkey and timeout
                    $this->request->data['passkey'] = null;
                    $this->request->data['timeout'] = null;
                    $user= $this->Users->patchEntity($user, $this->request->data);
                    if ($this->Users->save($user)) {
                        $this->Flash->set(__("Your password has been update."));
                        return $this->redirect(['action'=>'login']);
                    } else {
                        $this->Flash->error(__('The password not be updated. Please try again.'));
                    }
                }
            } else {
                $this->Flash->error(__('Invalid or expired passkey. Please check your email to try again!'));
                $this->redirect(['action', 'password']);
            }
            unset($user ->password);
            $this->set(compact('user'));
        } else {
            $this->redirect('/');
        }
    }
}
