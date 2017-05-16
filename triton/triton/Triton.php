<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 14.05.2017
 * Time: 01:03
 */
class Triton
{

    private $variables;
    protected static $db, $table, $id = 'id';
    protected  $add = null;

    public function __get($name)
    {
        return $this->variables[$name];
    }

    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    function __toString()
    {
        $str = '';
        foreach ($this->variables as $key => $value)
        {
            $str .= $key . " = " . $value . " <br/> \r\n";
        }
        return $str;
    }

    function __debugInfo()
    {
        return $this->variables;
    }

    public function add($data)
    {
        $callType = debug_backtrace()[0]['type'] == '::';
        if($callType == '::')
        {
            if (empty($GLOBALS['_neptune']['databases']))
            {
                require __DIR__ . '/../config/start.triton.php';
            }
            $column = '';
            foreach (array_keys($data) as $columnname)
            {
                $column .= $columnname . ' = ?, ';
            }
            $column = rtrim($column, ', ');
            $insert = $GLOBALS['_neptune']['databases'][static::$db]->prepare('INSERT INTO ' . static::$table . ' SET ' . $column);
            $result = $insert->execute(array_values($data));
            if($result !== false)
            {
                return $GLOBALS['_neptune']['databases'][static::$db]->lastInsertId();
            }
            return false;
        }
        else
        {
            $this->add[] = $data;
        }
    }

    public function save()
    {

        error_reporting(E_ALL);

        if(!empty($this->add))
        {
            if (empty($GLOBALS['_neptune']['databases']))
            {
                require __DIR__ . '/../config/start.triton.php';
            }
            $execute = [];
            $column = 'INSERT INTO ' . static::$table . '(';
            foreach (array_keys($this->add[0]) as $value)
            {
                $column .= $value . ',';
            }
            $column = rtrim($column, ',');
            $column .= ') VALUES';
            foreach ($this->add  as $dataKey => $data)
            {
                $column .= '(';
                foreach (array_keys($data) as $key => $columnname)
                {
                    $column .= ':' . $columnname . '_' . $dataKey . ', ';
                    $execute[$columnname . '_' . $dataKey] = $data[$columnname];
                }
                $column = rtrim($column, ', ');
                $column .= '),';
            }
            $column = rtrim($column, ', ');
            var_dump($column, $execute);
            $insert = $GLOBALS['_neptune']['databases'][static::$db]->prepare($column);
            $result = $insert->execute($execute);
            var_dump($result);
            if ($result !== false) {
                return $GLOBALS['_neptune']['databases'][static::$db]->lastInsertId();
            }
            return false;
        }
        else
        {

        }
    }

}