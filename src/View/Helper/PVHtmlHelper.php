<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * PVHtml helper
 */
class PVHtmlHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    public $helpers = ['Html'];
    public function link($title = null, $url = null)
    {
        if ($title == null || $url == null) {
            return;
        }

        $params = $this->request->params;
        $class = ($params['controller'] == $url['controller']) ? 'active' :'';
        echo "<li class='" . $class . "'>" . $this->Html->link($title, $url) . "</li>";
    }
}
