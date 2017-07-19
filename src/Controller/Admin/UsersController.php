<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Mailer\Email;
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
        $users = $this->paginate($this->Users);

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
        $departments_usersTable = TableRegistry::get('DepartmentsUsers');
        $departments_users = $departments_usersTable->newEntity();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $departments_users->department_id = $this->request->data('department_id');
            $departments_users->position= $this->request->data('position');

            //email content
            $mail =  $this->request->data('email');
            $subject = 'Account login system!';
            $message = 'Hi ' .$this->request->data('username'). '. Your information:
              Username :'.$this->request->data('username').',
              password: '.$this->request->data('password').'.
              Welcome!!!';

            //upload avatar
            if ($user->uploadAvatar($this->request->data['base64-avatar'], $this->request->data['avatar'])) {
                if ($this->Users->save($user)) {
                    //send email
                    $email = new Email();
                    $email->from(['tanhd070695@gmail.com'=>'Rikkeisoft'])
                      ->to($mail)
                      ->subject($subject)
                      ->send($message);
                    $departments_users->user_id =  $user->id;
                    if ($departments_usersTable->save($departments_users)) {
                        $this->Flash->success(__('The user has been saved.'));
                    } else {
                        $this->Flash->error(__('The user could not be saved. Please, try again.'));
                    }

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The avatar could not be saved. please try again.'));
            }
        }
        $departments = $this->Users->Departments->find('list');
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
}
