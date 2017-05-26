<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 14.05.2017
 * Time: 01:03
 */
class Triton
{

    private $variables = ['data' => [], 'type' => 'single'], $relation;
    protected static $db, $table, $id = 'id', $dataManipulation;

    public function setType($type)
    {
        $this->variables['type'] = $type;
    }

    public function setData($data)
    {
        $this->variables['data'] = $data;
    }

    public function hasMany($class)
    {
        $called = get_called_class();
        $reflection =  new \ReflectionClass($called);
        $func = strtolower($reflection->getShortName());
        $class = new $class();
        $class->setRelation($this);
        return $class->$func;
    }

    public function setRelation($object)
    {
        $this->relation = $object;
    }

    public function belongsTo($class)
    {
        $reflection =  new \ReflectionClass($class);
        $classShort = strtolower($reflection->getShortName());
        $id = self::$id;
        if(isset($this->relation))
        {
            $result = $class::where($classShort . '_id', $this->relation->variables['data'][$id])->execute();
        }
        else
        {
            $result = $class::where('id', $this->variables['data'][$classShort . '_' . $id])->execute();
        }
        return $result->variables['data'];
    }

    public static function where($column, $value, $mark = '=')
    {
        $class = new TritonWhere(self::class);
        $class->data['first'] = [$column, $value, $mark];
        $class->database = static::$db;
        $class->table = static::$table;
        $class->called = get_called_class();
        return $class;
    }

    public function __get($name)
    {
        if(method_exists($this, $name)) {
            return $this->$name();
        }
        else
        {
            $GLOBALS['result'] = [];
            if($this->variables['type'] == 'multi')
            {
                $GLOBALS['name'] = $name;
                array_map(function($object){
                    $name = $GLOBALS['name'];
                    $GLOBALS['result'][] = $object->$name;
                }, $this->variables['data']);
            }
            else if ($this->variables['type'] == 'single')
            {
                $GLOBALS['result'] = $this->variables['data'][$name];
            }
            return $GLOBALS['result'];
        }
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
                $model->setType('multi');
                $model->setData(unserialize($triton['all'][$file_name]));
                return $model->variables['data'];
            }
            else
            {
                $db = self::connectDatabase(static::$db);
                $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table);
                $result = ($type == 'object') ? $select->fetchAll(PDO::FETCH_CLASS, get_called_class()) : $select->fetchAll(PDO::FETCH_ASSOC);
                file_put_contents($file, '<?php $triton[\'all\'][\''. $file_name .'\'] = \'' . serialize($result) . '\';');
                if($type == 'object')
                {
                    $model = new $called_class;
                    $model->setType('multi');
                    $model->setData($result);
                    return $model->variables['data'];
                }
                else
                {
                    return  $result;
                }
            }
        }
        else
        {
            $db = self::connectDatabase(static::$db);
            $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table);
            $result = ($type == 'object') ? $select->fetchAll(PDO::FETCH_CLASS, get_called_class()) : $select->fetchAll(PDO::FETCH_ASSOC);
            file_put_contents($file, '<?php $triton[\'all\'][\''. $file_name .'\'] = \'' . serialize($result) . '\';');
            if($type == 'object')
            {
                $model = new $called_class;
                $model->setType('multi');
                $model->setData($result);
                return $model;
            }
            else
            {
                return  $result;
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
    {
        $GLOBALS['_neptune']['databases'] = null;
        /*
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


    public static function connectDatabase($dbname)
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