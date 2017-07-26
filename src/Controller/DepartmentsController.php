<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Departments Controller
 *
 * @property \App\Model\Table\DepartmentsTable $Departments
 */
class DepartmentsController extends AppController
{
    public $uses = ['Users'];
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $limit = LIMIT_PAGINATE;
        if ($this->request->is('post')) {
            if (in_array($this->request->data('recperpageval'),
        [5, 25, 50])) {
                $limit = $this->request->data('recperpageval');
            }
        }
        $departments = $this->Paginator->paginate($this->Departments, ['limit' =>$limit]);
        $this->set(compact('departments'));
        $this->set('_serialize', ['departments']);
    }

    /**
     * View method
     *
     * @param string|null $id Department id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function view($id = null)
     {
         $limit = LIMIT_PAGINATE;
         if ($this->request->is('post')) {
             if (in_array($this->request->data('recperpageval'),
       [5, 25, 50])) {
                 $limit = $this->request->data('recperpageval');
             }
         }
         $department = $this->Departments->get($id);
         $loggedUser=TableRegistry::get('Users')->get($this->Auth->user('id'));
         $users = $this->Departments->Users->find()->matching('Departments', function ($q) use ($department) {
             return $q->where(['Departments.id' => $department->id]);
         });
         $this->set('department', $department);
         $this->set('users', $this->Paginator->paginate($users, ['limit'=> $limit]));
         $this->set('loggedUser', $loggedUser);
         $this->set('_serialize', ['department']);
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
             $this->redirect(['controller'=> 'departments', 'action'=> 'index']);
         }
     }


    /**
     * Edit method
     *
     * @param string|null $id Department id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($this->request->session()->read('Auth.User.role') != true) {
            $this->Flash->error(__('Permission denied'));
            $this->redirect(['controller'=> 'departments', 'action'=> 'view',$id]);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Department id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($this->request->session()->read('Auth.User.role') != true) {
            $this->Flash->error(__('Permission denied'));
            $this->redirect(['controller'=> 'departments', 'action'=> 'view',$id]);
        }
    }
    public function export($id = null)
    {
        $department = $this->Departments->get($id, [
          'contain' => ['Users']
      ]);
        $data = [];
        foreach ($this->request->data as $key => $value) {
            if ($value != 0) {
                $user = $this->Departments->Users->findById($value)->toArray();
                array_push($data, $user[0]);
            }
        }
        if (empty($data)) {
            $this->Flash->error(__('Please choose Employees to export!'));
            $this->redirect(['controller' => 'Departments', 'action'=> 'view', $department->id]);
        }
        $fileName = $department->name.'.csv';
        $this->response->download($fileName);
        $_serialize = 'data';
        $_header = ['ID', 'UserName', 'Email','Gender', 'Date of Birth  '];
        $_extract = ['id', 'username', 'email','gender', 'dob'];
        $this->set(compact('data', '_serialize', '_header', '_extract'));
        $this->viewBuilder()->className('CsvView.Csv');
        return;
    }
}
