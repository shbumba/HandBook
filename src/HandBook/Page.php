<?php
namespace HandBook;

class Page
{
    protected $first = null;
    protected $last = null;
    protected $hand = null;

    public function __construct(array $data = array())
    {
        foreach ($data as $key => $val) {
            $this->{$key} = $val;
        }
    }

    public function get($field)
    {
        return isset($this->{$field}) ? $this->{$field} : null;
    }
}