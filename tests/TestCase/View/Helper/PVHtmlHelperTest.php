<?php
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\PVHtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\PVHtmlHelper Test Case
 */
class PVHtmlHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\View\Helper\PVHtmlHelper
     */
    public $PVHtml;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->PVHtml = new PVHtmlHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PVHtml);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
