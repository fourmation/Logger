<?php
namespace Logger\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class Add
 *
 * @package Logger\View\Helper
 */
class Add extends AbstractHelper
{
    protected $mapper;

    /**
     * @var
     */
    protected $serviceLocator;

    /**
     * @param $comment
     * @param string $commentType
     * @param null $linkTable
     * @param null $link_id
     * @return bool
     */
    public function __invoke($comment, $commentType = 'notice', $linkTable = null, $linkId = null)
    {
        $fields = array(
            'comment'=>$comment,
            'comment_type'=>$commentType,
            'link_table'=>$linkTable,
            'link_id'=>$linkId
        );

        $mapper = $this->mapper;
        $result = $mapper->save($fields);

        if (! empty($result)) {
            return $result['log_id'];
        }

        return false;
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'MyViewHelper' => function($sm) {
                    $sm = $sm->getServiceLocator(); // $sm was the view helper's locator
                    $table = $sm->get('MyModule_MyTable');

                    $helper = new MyModule\View\Helper\MyHelper($table);
                    return $helper;
                }
            )
        );
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}

