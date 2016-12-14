<?php
namespace HandBook;

class NoteBook
{
    private $id = null;
    private $sheets = array();

    public function __construct($id)
    {
        $this->setID($id);
    }

    public function setID($id)
    {
        $this->id = (int) $id;
    }

    public function addSheet($id, array $pages = array())
    {
        $this->sheets[$id] = new Sheet($id, $pages);
    }

    public function getSheet($id)
    {
        return isset($this->sheets[$id]) ? $this->sheets[$id] : null;
    }

    public function getSheets()
    {
        return $this->sheets;
    }

    public function getID()
    {
        return $this->id;
    }
}