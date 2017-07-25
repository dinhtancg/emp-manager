<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Departments Controller
 *
 * @property \App\Model\Table\DepartmentsTable $Departments
 */
class DepartmentsController extends AppController
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

        $this->set('department', $department);
        $this->set('_serialize', ['department']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $department = $this->Departments->newEntity();
        if ($this->request->is('post')) {
            $department = $this->Departments->patchEntity($department, $this->request->data);
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The department could not be saved. Please, try again.'));
            }
        }
        $users = $this->Departments->Users->find('list', ['limit' => QUERY_LIMIT]);
        $this->set(compact('department', 'users'));
        $this->set('_serialize', ['department']);
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
        $department = $this->Departments->get($id, [
            'contain' => ['Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $department = $this->Departments->patchEntity($department, $this->request->data);
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('The department could not be saved. Please, try again.'));
            }
        }
        $users = $this->Departments->Users->find('list', ['limit' => QUERY_LIMIT]);
        $this->set(compact('department', 'users'));
        $this->set('_serialize', ['department']);
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
        $this->request->allowMethod(['post', 'delete']);
        $department = $this->Departments->get($id);
        if ($this->Departments->delete($department)) {
            $this->Flash->success(__('The department has been deleted.'));
        } else {
            $this->Flash->error(__('The department could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    /**
     * [up to Manager]
     * @param  [type] $department_id [description]
     * @param  [type] $user_id       [description]
     * @return [type]                [description]
     */
    public function manager($department_id, $user_id)
    {
        $this->autoRender = false;
        $record = TableRegistry::get('DepartmentsUsers')
          ->find('all')
          ->where(['user_id' => $user_id, 'department_id' => $department_id])->toArray();

          // 0  is not manager
          // 1 is manager
        if ($record[0]->manager == 0) {
            $record[0]->manager = 1;
        } else {
            $record[0]->manager = 0;
        }
        if (TableRegistry::get('DepartmentsUsers')->save($record[0])) {
            $this->Flash->success(_('Change manager success'));
            return $this->redirect(['controller' => 'Departments', 'action' => 'view', $department_id]);
        } else {
            $this->Flash->error(_('Change manager false'));
            return $this->redirect(['controller' => 'Departments', 'action' => 'view', $department_id]);
        }
    }
}
