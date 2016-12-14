<?php
namespace HandBook;

class Check
{
    public static $defaultNum = 2;
    public static $data = array();
    public static $rule = array();
    public static $sheetHands = array();

    private static $error = array();

    private static function mode($key, &$num)
    {
        $mode = isset(self::$rule[$key]) ? self::$rule[$key] : self::$rule['*'];

        if (strpos($mode, '%') !== false) {
            $num = self::multiples($mode);
        }

        return $mode;
    }

    private static function multiples(&$mode)
    {
        $num = self::$defaultNum;

        if (preg_match('/%(\d+)/u', $mode, $match)) {
            $num = (int) preg_replace('/%(\d+)/u', '$1', $mode);
            $mode = '%';
        }

        if ($mode === '%' && $num <= 0) {
            $mode = self::$rule['*'];
        }

        return $num;
    }

    private static function field($mode, &$val, $num)
    {
        $input = null;
        $error = false;

        switch ($mode) {
            case 'int':
                $val = (int) $val;

                if ($error = !is_numeric($val)) {
                    $input = $val;
                }

                break;

            case '%':
                $val = (int) $val;

                if ($error = !($val % $num === 0)) {
                    $input = $mode . $num;
                }

                break;
        }

        return array($input, $error);
    }

    public static function key($key, $val)
    {
        switch ($key) {
            case 'sheetSize':
                self::keySheetSize($key, $val);
                break;
        }
    }

    private static function keySheetSize($key, $val)
    {
        if ($count = count(self::$sheetHands) !== $val) {
            self::addError('Variable $this->sheetHand should have a value of '. $val .', it has '. $count);

            return false;
        }

        return true;
    }

    public static function data()
    {
        foreach (self::$data as $key => &$val) {
            $matchNum = 2;

            $mode = self::mode($key, $matchNum);

            list($input, $error) = self::field($mode, $val, $matchNum);

            self::key($key, $val);

            if ($error) {
                self::addError(sprintf('Data validation error, the mode "%1$s" does not match the declared data. Data must be "%2$s" in key "%3$s"', $mode, $input, $key));
            }
        }
    }

    public static function addError($error)
    {
        $error = (array)$error;

        self::$error = array_merge(self::$error, $error);
    }

    public static function getErrors()
    {
        return self::$error;
    }
}