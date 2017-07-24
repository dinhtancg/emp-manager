<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DepartmentsManagersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DepartmentsManagersTable Test Case
 */
class DepartmentsManagersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DepartmentsManagersTable
     */
    public $DepartmentsManagers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.departments_managers',
        'app.users',
        'app.departments',
        'app.departments_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DepartmentsManagers') ? [] : ['className' => 'App\Model\Table\DepartmentsManagersTable'];
        $this->DepartmentsManagers = TableRegistry::get('DepartmentsManagers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DepartmentsManagers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
