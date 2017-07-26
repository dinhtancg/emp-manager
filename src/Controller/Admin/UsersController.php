<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

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
        $limit = 5;
        if ($this->request->is('post')) {
            if (in_array($this->request->data('recperpageval'),
      [5, 25, 50])) {
                $limit = $this->request->data('recperpageval');
            }
        }
        $users = $this->Paginator->paginate($this->Users, ['limit' =>$limit]);
        $this->set(compact('users'));
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
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $mail =  $this->request->data('email');
            $subject = 'Account login system!';
            $message = 'Hi ' .$this->request->data('username'). '. Your information:
              Username :'.$this->request->data('username').',
              password: '. $this->request->data('password').'.
              Welcome!!!';
            if ($user->uploadAvatar($this->request->data['base64-avatar'], $this->request->data['avatar'])) {
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
            } else {
                $this->Flash->error(__('The avatar could not be saved. please try again.'));
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
        if ($this->request->is('post')) {
            $arrayIds = $this->request->data['users']['_ids'];
            foreach ($arrayIds as $id) {
                $user = $this->Users->findById($id)->first();
                if (!$user) {
                    $this->Flash->error(__('User does not exist. Please try again!'));
                } else {
                    $pass_key = uniqid();
                    $url = Router::Url(['controller' =>'users', 'action' => 'reset'], true).'/'. $pass_key;
                    $url= str_replace("/admin", "", $url);
                    $timeout = time()+ DAY;
                    if ($this->Users->updateAll(['pass_key' => $pass_key, 'timeout' => $timeout], ['id' => $user->id])) {
                        $this->sendResetEmail($url, $user);
                    } else {
                        $this->Flash->error('Error saving reset pass_key/ timeout');
                    }
                }
            }
        }
        $users = $this->Users->find('list', ['limit' => QUERY_LIMIT]);
        $this->set(compact('users'));
    }
}
