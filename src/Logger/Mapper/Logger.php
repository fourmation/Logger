<?php
namespace Logger\Mapper;

use Logger\Mapper\Exception\LoggerException as LoggerException;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class Logger
 *
 * @package Logger\Mapper
 */
Class Logger
{

    /**
     * @var bool - $isInitialized
     */
    protected $isInitialized = false;

    /**
     * @var - $dbAdapter
     */
    protected $dbAdapter;

    /**
     * @var string - $tableName
     */
    protected $tableName = 'logs';

    /**
     * @var null
     */
    protected $linkTable = null;

    /**
     * @var
     */
    protected $hydrator;

    /**
     * Performs some basic initialization setup and checks before running a query
     *
     * @return null
     */
    protected function initialize()
    {
        if ($this->isInitialized == true) {
            return;
        }

        if ($this->adapter instanceof Zend\Db\Adapter\Adapter) {
            throw new LoggerException('No db adapter present');
        }

        $this->isInitialized = true;
    }

    public function find()
    {
        $this->initialize();

        $hydrator = $this->getHydrator();
        $entity = $this->getEntity();

        $values = $hydrator->extract($entity);

        foreach ($values as $key=>$value) {
            if (is_null($value)) unset($values[$key]);
        }

        $sql = NEW Sql($this->getAdapter());
        $select = $sql->select($this->getTableName());
        $select->where($values);

        return $this->getResult($sql, $select, false);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function findByLogId($id)
    {
        $this->initialize();

        $sql = NEW Sql($this->getAdapter());
        $select = $sql->select()->from($this->tableName)->where(array('log_id' => $id));

        return $this->getResult($sql, $select, false);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function findById($id)
    {
        $this->initialize();

        $sql = NEW Sql($this->getAdapter());

        $where = array('link_id' => $id);

        // Add link table identifier if applicable
        if (! is_null($this->getLinkTable())) {
            $where_array['link_table'] = $this->getLinkTable();
        }

        $select = $sql->select()->from($this->tableName)->where($where);

        return $this->getResult($sql, $select);
    }

    /**
     * @param $type
     * @param null $id
     *
     * @return array
     */
    public function findByType($type, $id = null)
    {
        $this->initialize();

        $sql = NEW Sql($this->getAdapter());

        $where = array('comment_type' => $type);

        // Add link table identifier if applicable
        if (! is_null($this->getLinkTable())) {
            $where['link_table'] = $this->getLinkTable();
        }

        if (! is_null($id)) {
            $where['link_id'] = $id;
        }

        $select = $sql->select()->from($this->tableName)->where($where);

        return $this->getResult($sql, $select, false);
    }

    /**
     * @param $data
     *
     * @return array|bool
     */
    public function save($data)
    {
        $this->initialize();

        $hydrator = $this->getHydrator();

        if (! is_object($data)) {
            $entity = $hydrator->hydrate($data, $this->getEntity());
        }
        else {
            $entity = $data;
        }

        $sql = NEW Sql($this->getAdapter());

        if ($entity->getLogId() == null) {
            // Insert
            $insert = $sql->insert($this->tableName)->values($hydrator->extract($entity));
            $row_id = $sql->prepareStatementForSqlObject($insert)->execute()->getGeneratedValue();
        }
        else {
            // Update
            $update = $sql->update($this->tableName)->set($hydrator->extract($entity))->where(array('log_id' => $entity->getLogId()));
            $sql->prepareStatementForSqlObject($update)->execute();
            $row_id = $entity->getLogId();
        }

        // Return resulting row
        if ($row_id != 0) {
            return $this->findByLogId($row_id);
        }

        return false;
    }

    /**
     * @param $sql
     * @param $select
     * @param bool $hydrate
     *
     * @return array
     */
    public function getResult($sql, $select, $hydrate = true)
    {
        $results = $sql->prepareStatementForSqlObject($select)->execute();

        $resultSet = new ResultSet;
        $result = $resultSet->initialize($results)->toArray();
        $entity_array = array();

        foreach ($result as $item) {
            if ($hydrate) {
                $entity_array[] = $this->getHydrator()->hydrate($item, $this->getEntity());
            }
            else {
                $entity_array[] = $item;
            }
        }

        return $entity_array;
    }

    /**
     * @param $message
     * @param string $type
     *
     * @return $this
     */
    public function message($message, $type = 'notice')
    {
        $entity = $this->getEntity()->setComment($message);
        $entity = $this->getEntity()->setCommentType($type);

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function messageType(string $type)
    {
        $this->getEntity()->setCommentType($type);

        return $this;
    }

    /**
     * @param $linkType
     * @param $linkId
     *
     * @return $this
     */
    public function link($linkType, $linkId)
    {
        $this->getEntity()->setLinkTable($linkType);
        $this->getEntity()->setLinkId($linkId);

        return $this;
    }

    /**
     * @param $linkType
     * @param $linkId
     *
     * @return $this
     */
    public function id($logId)
    {
        $this->getEntity()->setLogId($logId);
        return $this;
    }

    /**
     * @return integer $row
     */
    public function write()
    {
        return $this->save($this->getEntity());
    }

    /**
     * @return integer $row
     */
    public function getAll()
    {
        return $this->save($this->getEntity());
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param $tablename
     *
     * @return $this
     */
    public function setTableName($tablename)
    {
        $this->tableName = $tablename;

        return $this;
    }

    /**
     * @return null
     */
    public function getLinkTable()
    {
        return $this->linkTable;
    }

    /**
     * @param $linkTable
     *
     * @return $this
     */
    public function setLinkTable($linkTable)
    {
        $this->linkTable = $linkTable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param $adapter
     *
     * @return $this
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param $hydrator
     *
     * @return $this
     */
    public function setHydrator($hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
