<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 14.05.2017
 * Time: 01:03
 */
class Triton
{

    private $variables = ['data' => [], 'type' => 'single'];
    protected static $db, $table, $id = 'id', $dataManipulation;

    public function setType($type)
    {
        $this->variables['type'] = $type;
    }

    public function setData($data)
    {
        $this->variables['data'] = $data;
    }

    public function __get($name)
    {
        $GLOBALS['result'] = [];
        $GLOBALS['name'] = $name;
        array_map(function($object){
            $name = $GLOBALS['name'];
            $GLOBALS['result'][] = $object->$name;
        }, $this->variables['data']);
        return $GLOBALS['result'];
    }

    public function __set($name, $value)
    {
        $this->variables['data'][$name] = $value;
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

    public static function all($columns = '*', $type = 'object')
    {
        $directory = TRITON_ROOT . '/temp';
        $file_name = md5(__FUNCTION__ . $columns . $type);
        $file = $directory . DIRECTORY_SEPARATOR . $file_name . '.php';
        $called_class = get_called_class();
        if(file_exists($file))
        {
            if(filectime($file) == time() and filemtime($file) == time())
            {
                if(!isset($triton[$file_name])) {
                    require $file;
                }
                $model = new $called_class;
                $model->setType('object');
                $model->setData(json_decode($triton['all'][$file_name]));
                return $model;
            }
            else
            {
                $db = self::connectDatabase(static::$db);
                $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table);
                if($type == 'object')
                {
                    $result = $select->fetchAll(PDO::FETCH_OBJ);
                    $model = new $called_class;
                    $model->setType('object');
                    $model->setData($result);
                    file_put_contents($file, '<?php $triton[\'all\'][\''. $file_name .'\'] = \'' . json_encode($result) . '\';');
                    return $model;
                }
            }
        }
        else
        {
            $db = self::connectDatabase(static::$db);
            $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table);
            if($type == 'object')
            {
                $result = $select->fetchAll(PDO::FETCH_OBJ);
                var_dump($result);
                var_dump(json_encode($result));
                $model = new $called_class;
                $model->setType('object');
                $model->setData($result);
                file_put_contents($file, '<?php $triton[\'all\'][\''. $file_name .'\'] = \'' . json_encode($result) . '\';');
            }
        }
    }

    public static function add($data)
    {
        static::$dataManipulation['add'][] = $data;
    }

    public function save()
    {
        error_reporting(E_ALL);

    }

    public function __destruct()
    {   /*
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
        */
    }

    private static function connectDatabase($dbname)
    {
        global $configs, $databases;

        if(file_exists(TRITON_ROOT . '/../' . $configs['databases']))
        {
            require_once TRITON_ROOT . '/../' . $configs['databases'];
        }
        else
        {
            require_once __DIR__ . '/../' . $configs['databases'];

        }
        $database = $databases[$dbname];
        if($database['driver'] == 'mysql' || $database['driver'] == 'mariadb')
        {
            return $GLOBALS['_neptune']['databases'][$dbname] = new \PDO('mysql:host=' . $database['host'] . ';dbname=' . $dbname . ';charset=' . $database['charset'], $database['user'], $database['pass']);
        }
    }

}