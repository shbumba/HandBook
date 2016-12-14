<?php
namespace HandBook;

class Book
{
    public $data = array();
    private $result = array();
    protected $rule = array(
        'total' => 'int',
        'pageSize' => '%2',
        'sheetSize' => '%2',
        'notebookSheet' => 'int',
        '*' => 'int'
    );
    protected $sheetHands = array(
        0 => 'front',
        1 => 'back'
    );
    protected $error = array();

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    public function run()
    {
        $this->check();
        $this->calculate();

        return $this;
    }

    private function check()
    {
        Check::$data = $this->data;
        Check::$rule = $this->rule;
        Check::$sheetHands = $this->sheetHands;

        Check::data();

        $this->addError(Check::getErrors());
    }

    public function calculate()
    {
        $diffArray = $this->rule;
        unset($diffArray['*']);

        $diffArray = array_diff_key($diffArray, $this->data);

        if (count($diffArray) > 0) {
            throw new Exception('The key `'. implode(';', $diffArray) .'` is not defined.', $this->getErrors());
        }

        if (count($this->error) > 0) {
            throw new Exception('Correct the mistakes. Use ->getErrors()', $this->getErrors());
        }

        $sheetAll = (int) ceil($this->data['total'] / ($this->data['pageSize'] + $this->data['sheetSize'])); //Сколько нужно всего листов
        $numOfPagesNotebook = (int) ($this->data['notebookSheet'] * $this->data['pageSize'] * $this->data['sheetSize']); //количество страниц на тетрадь

        $countSheet = 0; //Начинаем отсчет использованных страниц
        $countNotebook = 1; //Начинаем отсчет использованных тетрадей

        $result = array();

        while (true) {
            ++$countSheet;

            if (!isset($result[$countNotebook])) {
                $result[$countNotebook] = new NoteBook($countNotebook);
            }

            if ($result[$countNotebook]->getSheet($countSheet) === null) {
                $result[$countNotebook]->addSheet($countSheet, array());
            }

            $currentSheet = $result[$countNotebook]->getSheet($countSheet);
            $currentResult = &$currentSheet;

            $currentNotebook = $countNotebook; //Текущая тетрадь

            $lastPageNum = $numOfPagesNotebook * $currentNotebook; //Последний номер страницы
            $firstPageNum = ($lastPageNum + 1) - $numOfPagesNotebook; //Первый номер страницы

            $shift = $countSheet % $this->data['notebookSheet'];
            $shift = $shift > 0 ? $shift : $this->data['notebookSheet'];
            $shift = ($shift - 1) * $this->data['sheetSize']; //Сдвиг рассчета

            for ($i = 0; $i < $this->data['sheetSize']; $i++) {
                $currentResult->addPage($this->sheetHands[$i], array(
                    'first' => $firstPageNum + ($i + $shift),
                    'last' => $lastPageNum - ($i + $shift),
                ));
            }

            if ($countSheet % $this->data['notebookSheet'] === 0) {
                $countNotebook++;
            }

            if ($countSheet === $sheetAll) {
                break;
            }
        }

        $this->result = $result;
    }

    public function setRule(array $fields)
    {
        $this->rule = array_merge($this->rule, $fields);
    }

    public function setHands(array $hands)
    {
        $this->sheetHands = $hands;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function addError($error)
    {
        $error = (array)$error;

        $this->error = array_merge($this->error, $error);
    }

    public function getErrors()
    {
        return $this->error;
    }
}