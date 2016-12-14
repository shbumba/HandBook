<?php
namespace HandBook;

class Sheet
{
    private $id = null;
    private $pages = array();
    private $hands = array();

    public function __construct($id, array $pages = array())
    {
        $this->setID($id);

        foreach ($pages as $hand => $data) {
            $this->addPage($hand, $data);
        }
    }

    public function addPage($hand, array $data)
    {
        $page = new Page(array_merge($data, array('hand' => $hand)));

        $this->addHand($hand);

        $this->pages[] = $this->hands[$hand] = $page;
    }

    public function addHand($hand)
    {
        if (!isset($this->hands[$hand])) {
            $this->hands[$hand] = array();
        }

        return $this->hands[$hand];
    }

    public function setID($id)
    {
        $this->id = (int) $id;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getPage($id)
    {
        return isset($this->pages[$id]) ? $this->pages[$id] : null;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function getHand($id)
    {
        return isset($this->hands[$id]) ? $this->hands[$id] : null;
    }

    public function getHands()
    {
        return $this->hands;
    }
}