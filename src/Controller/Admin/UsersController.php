<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;

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
        if (array_key_exists('limit', $this->request->query) && in_array($this->request->query['limit'], [10,20,50])) {
            $limit = $this->request->query['limit'];
            $this->request->session()->write('users.index.limit', $limit);
            $sessionLimit = $this->request->session()->read('users.index.limit');
        }
        if (isset($this->request->query['search']) && !empty($this->request->query)) {
            $search_key = trim($this->request->query['search']);
            $conditions= [
                "OR" => [
                  'username LIKE' => '%' .$search_key. '%',
                  'email LIKE' => '%' . $search_key . '%',
                  'full_name LIKE' => '%' . $search_key . '%'
                ]];
        } else {
            $conditions = null;
            $search_key =null;
        }
        $users = $this->Paginator->paginate($this->Users, ['conditions' => $conditions, 'limit' =>$limit]);
        $this->set(compact('users', 'sessionLimit', 'search_key'));
        $this->set('_serialize', ['users']);
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
        $user = TableRegistry::get('Users')->find()->where(['id'=> $id])->first();
        if (!$user) {
            $this->Flash->error(__('User not found!'));
            $this->redirect(['controller'=> 'users', 'action'=> 'index']);
        } else {
            $departments = $this->Users->Departments->find()->matching('Users', function ($q) use ($user) {
                return $q->where(['Users.id' => $user->id]);
            });
            $this->set('user', $user);
            $this->set('sessionLimit', $sessionLimit);
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
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $mail =  $this->request->data('email');
            $subject = 'Account login system!';
            $message = 'Hi ' .$this->request->data('username'). '. Your information:
              Username :'.$this->request->data('username').',
              password: '. $this->request->data('password').'.
              Welcome!!!';
            if ($this->Users->save($user)) {
                $email = new Email();
                $email->from(['tanhd070695@gmail.com'=>'Rikkeisoft'])
                      ->to($mail)
                      ->subject($subject)
                      ->send($message);
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $departments = $this->Users->Departments->find('list', ['limit' => QUERY_LIMIT]);
        $this->set(compact('user', 'departments'));
        $this->set('_serialize', ['user']);
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
        $user = $this->Users->get($id, [
            'contain' => ['Departments']
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
        $departments = $this->Users->Departments->find('list', ['limit' => QUERY_LIMIT]);
        $this->set(compact('user', 'departments'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    /**
     * Reset password of user
     * @return [type] [sent email to user's email]
     */
    public function password()
    {
        $datas = $this->request->data();
        $users = [];
        if (array_key_exists('checkall', $datas)) {
            $not = [1];
            foreach ($datas as $key => $value) {
                if (empty($value)) {
                    $not[] = $key;
                }
            }
            $users = $this->Users->find('all')
              ->where(['Users.id NOT IN' => $not])
              ->toArray();
        } else {
            $ids = [];
            foreach ($datas as $key => $value) {
                if (!empty($value)) {
                    $ids[] = $key;
                }
            }
            if (empty($ids)) {
                $this->Flash->error(__('Please choose Employees to reset password!'));
                $this->redirect(['controller' => 'Users', 'action'=> 'index']);
            } else {
                $users = $this->Users->find('all')
                ->where(['Users.id IN' => $ids])
                ->toArray();
            }
        }
        if (empty($users)) {
            $this->Flash->error(__('Please choose Employees to reset password!'));
            $this->redirect(['controller' => 'Users', 'action'=> 'index']);
        }
        foreach ($users as $user) {
            $pass_key = uniqid();
            if ($this->Users->updateAll(['password' => (new DefaultPasswordHasher)->hash($pass_key), 'first_login' => false], ['id' => $user->id])) {
                $this->sendResetEmail($pass_key, $user);
            } else {
                $this->Flash->error('Error saving reset pass_key');
            }
        }
        $this->redirect(['controller' => 'Users', 'action'=> 'index']);
    }
}
