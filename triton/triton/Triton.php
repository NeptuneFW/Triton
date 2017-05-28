<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 14.05.2017
 * Time: 01:03
 */
class Triton
{

    protected static $db, $table, $id = 'id', $dataManipulation;
    private $variables = ['data' => ['triton' => ['insert' => true]], 'type' => 'single'], $relation;

    public static function find($id, $columns = '*', $type = 'object')
    {
        $directory = TRITON_ROOT . '/temp';
        $file_name = md5(__FUNCTION__ . $id . $columns . $type);
        $file = $directory . DIRECTORY_SEPARATOR . $file_name . '.php';
        $called_class = get_called_class();
        if (file_exists($file)) {
            if (filectime($file) == time() and filemtime($file) == time()) {
                if (!isset($triton[$file_name])) {
                    require $file;
                }

                $model = new $called_class;
                $model->setType('single');
                $model->setData(unserialize($triton['find'][$file_name])[0]->variables['data']);
                $model->noInsert();
                return $model;
            } else {
                $db = self::connectDatabase(static::$db);
                $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table . ' WHERE ' . static::$id . ' = \'' . $id . '\'');
                $result = ($type == 'object') ? $select->fetchAll(PDO::FETCH_CLASS, get_called_class()) : $select->fetchAll(PDO::FETCH_ASSOC);
                file_put_contents($file, '<?php $triton[\'find\'][\'' . $file_name . '\'] = \'' . serialize($result) . '\';');
                if ($type == 'object') {
                    $model = new $called_class;
                    $model->setType('single');
                    $model->setData($result[0]->variables['data']);
                    $model->noInsert();
                    return $model;
                } else {
                    return $result;
                }
            }
        } else {
            $db = self::connectDatabase(static::$db);
            $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table . ' WHERE ' . static::$id . ' = \'' . $id . '\'');
            $result = ($type == 'object') ? $select->fetchAll(PDO::FETCH_CLASS, get_called_class()) : $select->fetchAll(PDO::FETCH_ASSOC);
            file_put_contents($file, '<?php $triton[\'find\'][\'' . $file_name . '\'] = \'' . serialize($result) . '\';');
            if ($type == 'object') {
                $model = new $called_class;
                $model->setType('single');
                $model->setData($result[0]->variables['data']);
                $model->noInsert();
                return $model;
            } else {
                return $result;
            }
        }
    }

    public static function connectDatabase($dbname)
    {
        global $configs, $databases;

        if (file_exists(TRITON_ROOT . '/../' . $configs['databases'])) {
            require_once TRITON_ROOT . '/../' . $configs['databases'];
        } else {
            require_once __DIR__ . '/../' . $configs['databases'];

        }
        $database = $databases[$dbname];
        if ($database['driver'] == 'mysql' || $database['driver'] == 'mariadb') {
            return $GLOBALS['_neptune']['databases'][$dbname] = new \PDO('mysql:host=' . $database['host'] . ';dbname=' . $dbname . ';charset=' . $database['charset'], $database['user'], $database['pass']);
        }
    }

    public static function all($columns = '*', $type = 'object')
    {
        $directory = TRITON_ROOT . '/temp';
        $file_name = md5(__FUNCTION__ . $columns . $type);
        $file = $directory . DIRECTORY_SEPARATOR . $file_name . '.php';
        $called_class = get_called_class();
        if (file_exists($file)) {
            if (filectime($file) == time() and filemtime($file) == time()) {
                if (!isset($triton[$file_name])) {
                    require $file;
                }
                $model = new $called_class;
                $model->setType('multi');
                $model->setData(unserialize($triton['all'][$file_name]));
                return $model->variables['data'];
            } else {
                $db = self::connectDatabase(static::$db);
                $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table);
                $result = ($type == 'object') ? $select->fetchAll(PDO::FETCH_CLASS, get_called_class()) : $select->fetchAll(PDO::FETCH_ASSOC);
                if(empty($result)) return false;
                file_put_contents($file, '<?php $triton[\'all\'][\'' . $file_name . '\'] = \'' . serialize($result) . '\';');
                if ($type == 'object') {
                    $model = new $called_class;
                    $model->setType('multi');
                    $model->setData($result);
                    return $model->variables['data'];
                } else {
                    return $result;
                }
            }
        } else {
            $db = self::connectDatabase(static::$db);
            $select = $db->query('SELECT ' . $columns . ' FROM ' . static::$table);
            $result = ($type == 'object') ? $select->fetchAll(PDO::FETCH_CLASS, get_called_class()) : $select->fetchAll(PDO::FETCH_ASSOC);
            if(empty($result)) return false;
            file_put_contents($file, '<?php $triton[\'all\'][\'' . $file_name . '\'] = \'' . serialize($result) . '\';');
            if ($type == 'object') {
                $model = new $called_class;
                $model->setType('multi');
                $model->setData($result);
                return $model->variables['data'];
            } else {
                return $result;
            }
        }
    }

    public static function add($data, $notExists = null)
    {
        $db = self::connectDatabase(static::$db);
        if (!empty($notExists)) {
            $called_class = get_called_class();
            $where = $called_class::where($notExists[0], $data[$notExists[0]]);
            unset($notExists[0]);
            foreach ($notExists as $key => $column) {
                $where->andWhere($column, $data[$column]);
            }
            $whereResult = $where->execute('*', 'array');
            if ($whereResult != false) {
                throw new \Exception('There is.', 0);
            }
        }
        $column = '';
        foreach (array_keys($data) as $columnname) {
            $column .= $columnname . ' = ?, ';
        }
        $column = rtrim($column, ', ');
        $insert = $db->prepare('INSERT INTO ' . static::$table . ' SET ' . $column);
        $result = $insert->execute(array_values($data));
        if ($result !== false) {
            return $db->lastInsertId();
        }
    }

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
        $reflection = new \ReflectionClass($called);
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
        $reflection = new \ReflectionClass($class);
        $classShort = strtolower($reflection->getShortName());
        $id = self::$id;
        if (isset($this->relation)) {
            $result = $class::where($classShort . '_id', $this->relation->variables['data'][$id])->execute();
            var_dump($class, $classShort, $this->relation->variables['data'][$id], $id, $result);
        } else {
            $result = $class::where('id', $this->variables['data'][$classShort . '_' . $id])->execute();
        }
        return $result->variables['data'];
    }

    public function belongsToMany($class)
    {
        if (!isset($this->relation)) {
            $called = get_called_class();
            $reflection = new \ReflectionClass($called);
            $func = strtolower($reflection->getShortName());
            $class = new $class();
            $class->setRelation($this);
            return $class->$func();
        } else {
            $reflection = new \ReflectionClass($class);
            $classShort = strtolower($reflection->getShortName());
            $id = self::$id;
            if (isset($this->relation)) {
                $result = self::where($classShort . '_id', $this->relation->variables['data'][$id])->execute();
            } else {
                $result = $class::where('id', $this->variables['data'][$classShort . '_' . $id])->execute();
            }
            if ($result != false) return $result->variables['data'];
            return [];
        }
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

    public function delete()
    {
        if (isset($this->variables['data'][static::$id])) {
            $db = self::connectDatabase(static::$db);
            $select = $db->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$id . ' = :id');
            $result = $select->execute(['id' => $this->variables['data'][static::$id]]);
            if ($result !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        } else {
            $GLOBALS['result'] = [];
            if ($this->variables['type'] == 'multi') {
                $GLOBALS['name'] = $name;
                array_map(function ($object) {
                    $name = $GLOBALS['name'];
                    $GLOBALS['result'][] = $object->$name;
                }, $this->variables['data']);
            } else if ($this->variables['type'] == 'single') {
                $GLOBALS['result'] = $this->variables['data'][$name];
            }
            return $GLOBALS['result'];
        }
    }

    public function __set($name, $value)
    {
        if ($this->variables['type'] == 'single') {
            $this->variables['data'][$name] = $value;
            $this->variables['changed'][$name] = $name;
        }
    }

    function __toString()
    {
        $str = '';
        foreach ($this->variables as $key => $value) {
            $str .= $key . " = " . $value . " <br/> \r\n";
        }
        return $str;
    }

    function __debugInfo()
    {
        return $this->variables;
    }

    public function save()
    {
        $db = self::connectDatabase(static::$db);
        $column = '';
        $data = [];
        if ($this->variables['type'] == 'single') {
            if ($this->variables['data']['triton']['insert']) {
                foreach ($this->variables['changed'] as $columnname) {
                    $column .= $columnname . ' = ?, ';
                    $data[] = $this->variables['data'][$columnname];
                }
                $column = rtrim($column, ', ');
                $insert = $db->prepare('INSERT INTO ' . static::$table . ' SET ' . $column);
                $result = $insert->execute(array_values($data));
                if ($result !== false) {
                    return $db->lastInsertId();
                }
            } else {
                foreach ($this->variables['changed'] as $columnname) {
                    $column .= $columnname . ' = ?, ';
                    $data[] = $this->variables['data'][$columnname];
                }
                $column = rtrim($column, ', ');
                $insert = $db->prepare('UPDATE ' . static::$table . ' SET ' . $column);
                $result = $insert->execute(array_values($data));
                if ($result !== false) {
                    return $db->lastInsertId();
                }
            }
            return false;
        }
    }

    public function __destruct()
    {
        $GLOBALS['_neptune']['databases'] = null;
    }

}