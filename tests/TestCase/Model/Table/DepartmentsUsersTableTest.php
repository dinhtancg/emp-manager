<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DepartmentsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DepartmentsUsersTable Test Case
 */
class DepartmentsUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DepartmentsUsersTable
     */
    public $DepartmentsUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.departments_users',
        'app.departments',
        'app.departments__users',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DepartmentsUsers') ? [] : ['className' => 'App\Model\Table\DepartmentsUsersTable'];
        $this->DepartmentsUsers = TableRegistry::get('DepartmentsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DepartmentsUsers);

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
