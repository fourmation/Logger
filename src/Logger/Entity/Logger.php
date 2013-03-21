<?php

namespace Logger\Entity;

/**
 * Class Logger
 *
 * @package Logger\Entity
 */
class Logger
{

    /**
     * @var
     */
    protected $logId;

    /**
     * @var
     */
    protected $linkId;

    /**
     * @var
     */
    protected $linkTable;

    /**
     * @var
     */
    protected $comment;

    /**
     * @var string
     */
    protected $commentType;

    /**
     * @var
     */
    protected $created;

    /**
     * @param $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param $commentType
     * @return $this
     */
    public function setCommentType($commentType)
    {
        $this->commentType = $commentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentType()
    {
        return $this->commentType;
    }

    /**
     * @param $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $logId
     * @return $this
     */
    public function setLogId($logId)
    {
        $this->logId = $logId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogId()
    {
        return $this->logId;
    }

    /**
     * @param $linkId
     * @return $this
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * @param $linkTable
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
    public function getLinkTable()
    {
        return $this->linkTable;
    }

}