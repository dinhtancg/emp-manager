<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            'authorize' => ['Controller'],
        ]);
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['login','password','reset','resetSuccess']);
        if ($this->request->session()->read('Auth.User.id')) {
            $user = TableRegistry::get('Users')->find()->where(['id'=>$this->request->session()->read('Auth.User.id')])->first();
            if ($user['first_login'] != $this->request->session()->read('Auth.User.first_login')) {
                $this->redirect('/users/logout');
            }
        }

        if (array_key_exists('prefix', $this->request->params)) {
            if ($this->request->params['prefix'] == 'admin' && $this->request->session()->read('Auth.User.role') == false) {
                $this->Flash->error(__('You can not access permissions!'));
                return $this->redirect(['prefix'=> false, 'controller'=>'Users', 'action' => 'index']);
            }
        }
    }
    public function isAuthorized($user = null)
    {
        // Any registered user can access public functions
        if (!$this->request->param('prefix')) {
            return true;
        }

        // Only admins can access admin functions
        if ($this->request->param('prefix') == 'admin') {
            return (bool)($user['role']);
        }

        // Default deny
        return false;
    }
    /**
     * Send email to user for reset password
     * @param string $url  Link reset password
     * @param  $user
     */
    public function sendResetEmail($url, $user)
    {
        $email = new Email();

        if (!$this->request->session()->read('Auth.User.id')) {
            $email-> template('resetpw');
        } else {
            $email-> template('adminresetpw');
        }
        $email->emailFormat('both');
        $email->from('tanhd070695@gmail.com');
        $email->to($user->email, $user->username);
        $email->subject('Reset your password');
        $email->viewVars(['url'=>$url, 'username'=> $user->username]);
        if ($email->send()) {
            if ($this->request->session()->read('Auth.User.role') == true) {
                $this->Flash->success(__(' Password has been reset !'));
            } else {
                $this->redirect('users/reset-success');
            }
        } else {
            $this->Flash->error(__('Error sending email :'). $email->smtpError);
        }
    }
    /**
     * [reset password medthod]
     * @param [type] $pass_key [description]
     */
    public function reset($pass_key = null)
    {
        if ($pass_key) {
            $query = $this->Users->find('all', ['conditions' => ['pass_key' => $pass_key, 'timeout >' =>time()]]);
            $user = $query->first();
            if ($user) {
                if (!empty($this->request->data)) {
                    //Clear pass_key and timeout
                    $this->request->data['pass_key'] = null;
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
                $this->Flash->error(__('Invalid or expired pass_key. Please check your email to try again!'));
                $this->redirect(['action', 'password']);
            }
            unset($user ->password);
            $this->set(compact('user'));
        } else {
            $this->redirect('/');
        }
    }
}
