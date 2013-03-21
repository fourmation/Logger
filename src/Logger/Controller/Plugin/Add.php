<?php
namespace Logger\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Add
 *
 * @package Logger\View\Helper
 */
class Add extends AbstractPlugin
{
    /**
     * @var
     */
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

    /**
     * @return array
     */
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

    /**
     * @param $mapper
     *
     * @return $this
     */public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}

