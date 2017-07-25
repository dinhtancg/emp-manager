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
        $limit = 5;
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
         $department = $this->Departments->get($id, [
             'contain' => ['Users']
         ]);
         $loggedUser=TableRegistry::get('Users')->get($this->Auth->user('id'));
         $this->set('department', $department);
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
         if ($this->request->session()->read('Auth.User.role') != 1) {
             // 1 is Admin
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
        if ($this->request->session()->read('Auth.User.role') != 1) {
            // 1 is Admin
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
        if ($this->request->session()->read('Auth.User.role') != 1) {
            // 1 is Admin
            $this->Flash->error(__('Permission denied'));
            $this->redirect(['controller'=> 'departments', 'action'=> 'view',$id]);
        }
    }
    public function export($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => ['Users']
        ]);
        $data = $department->users;
        $fileName = $department->name.'.csv';
        $this->response->download($fileName);
        $_serialize = 'data';
        $_header = ['ID', 'UserName', 'Email','Gender', 'DoB'];
        $_extract = ['id', 'username', 'email','gender', 'dob'];
        $this->set(compact('data', '_serialize', '_header', '_extract'));
        $this->viewBuilder()->className('CsvView.Csv');
        return;
    }
}
