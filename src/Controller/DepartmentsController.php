<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Departments Controller
 *
 * @property \App\Model\Table\DepartmentsTable $Departments
 */
require_once(ROOT . DS . 'vendor' . DS . 'phpexcel' . DS . 'PHPExcel.php');

require_once(ROOT . DS . 'vendor' . DS . 'phpexcel' . DS . 'PHPExcel'. DS. 'IOFactory.php');
use PHPExcel;
use PHPExcel_IOFactory;

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
        $sessionLimit = $this->request->session()->read('departments.index.limit');
        $limit = $sessionLimit ? $this->request->session()->read('departments.index.limit') : LIMIT_PAGINATE;
        if ($this->request->is('get')) {
            if (array_key_exists('limit', $this->request->query) && in_array($this->request->query['limit'], [10,20,50])) {
                $limit = $this->request->query['limit'];
                $this->request->session()->write('departments.index.limit', $limit);
                $sessionLimit = $this->request->session()->read('departments.index.limit');
            }
        }
        $departments = $this->Paginator->paginate($this->Departments, ['limit' =>$limit]);
        $this->set('sessionLimit', $sessionLimit);
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
         $sessionLimit = $this->request->session()->read('departments.view.limit');
         $limit = $sessionLimit ? $this->request->session()->read('departments.view.limit') : LIMIT_PAGINATE;
         if ($this->request->is('get')) {
             if (array_key_exists('limit', $this->request->query) && in_array($this->request->query['limit'], [10,20,50])) {
                 $limit = $this->request->query['limit'];
                 $this->request->session()->write('departments.view.limit', $limit);
                 $sessionLimit = $this->request->session()->read('departments.view.limit');
             }
         }
         $department = TableRegistry::get('Departments')->find()->where(['id'=>$id])->first();
         if (!$department) {
             $this->Flash->error(__('Department not found!'));
             $this->redirect(['controller'=> 'Departments', 'action'=> 'index']);
         } else {
             $department = $this->Departments->get($id);
             $loggedUser=TableRegistry::get('Users')->get($this->Auth->user('id'));
             $users = $this->Departments->Users->find()->matching('Departments', function ($q) use ($department) {
                 return $q->where(['Departments.id' => $department->id]);
             });
             $this->set('department', $department);
             $this->set('sessionLimit', $sessionLimit);
             $this->set('users', $this->Paginator->paginate($users, ['limit'=> $limit]));
             $this->set('loggedUser', $loggedUser);
             $this->set('_serialize', ['department']);
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
    /**
     * export Employees of Departments to csv file
     * @param  [type] $id [description]
     * @return [file] file csv[description]
     */
    public function exportUser($id = null)
    {
        $datas = $this->request->data;
        $department = $this->Departments->get($id, [
          'contain' => ['Users']
      ]);
        $data = [];
        if (array_key_exists('checkall', $datas)) {
            $not = [];
            foreach ($datas as $key => $value) {
                if (empty($value)) {
                    $not[] = $key;
                }
            }
            if (empty($not)) {
                $data = $department->users;
            } else {
                $data = $this->Departments->Users->find('all')
              ->where(['Users.id NOT IN' => $not])
              ->matching('Departments', function ($q) use ($department) {
                  return $q->where(['Departments.id' => $department->id]);
              })->toArray();
            }
        } else {
            $ids = [];
            foreach ($datas as $key => $value) {
                if (!empty($value)) {
                    $ids[] = $key;
                }
            }
            $data = $this->Departments->Users->find('all')
              ->where(['Users.id IN' => $ids])
              ->matching('Departments', function ($q) use ($department) {
                  return $q->where(['Departments.id' => $department->id]);
              })->toArray();
        }
        if (empty($data)) {
            $this->Flash->error(__('Please choose Employees to export!'));
            $this->redirect(['controller' => 'Departments', 'action'=> 'view', $department->id]);
        }

        // // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("TanHD");


        //HEADER
        //apply the style on column A row 1 to Column B row 1
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(['font' => ['size' => 12,'bold' => true,'color' => ['rgb' => '#000000']]]);
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('696969');
        $i=1;
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, ' ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, ' UserName');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, ' Email');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, ' Gender');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, ' Birthday');

        //DATA
        foreach ($data as $employee) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $employee->id);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $employee->username);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $employee->email);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $employee->gender);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, date('d-m-Y', strtotime($employee->birthday)));
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Employees Data');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $fileName = $department->name.'.xlsx';


        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    }
}
