<?php

/**
 * Created by PhpStorm.
 * User: Peker
 * Date: 25.05.2017
 * Time: 21:20
 */
class TritonWhere
{

    public $data = ['first' => null, 'and' => null, 'or' => null], $class = '', $database, $table, $called = '';

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function execute($columns = '*', $type = 'object')
    {
        $directory = TRITON_ROOT . '/temp';
        $file_name = md5(__FUNCTION__ . serialize($this->data) . $this->database . $this->table);
        $file = $directory . DIRECTORY_SEPARATOR . $file_name . '.php';
        $called_class = get_called_class();

        if(file_exists($file))
        {
            if(filectime($file) == time() and filemtime($file) == time())
            {
                if(!isset($triton[$file_name])) require $file;
                $model = new $this->called;
                $model->setType('multi');
                $model->setData(json_decode($triton['where']['execute'][$file_name]));
                return $model;
            }
            else {
                $db = $this->class::connectDatabase($this->database);
                $bind = [];
                $selectQuery = 'SELECT ' . $columns . ' FROM ' . $this->table . ' WHERE ' . $this->data['first'][0] . " " . $this->data['first'][2] . ":first ";
                $bind['first'] = $this->data['first'][1];
                $i = 0;
                if (isset($this->data['or']))
                {
                    foreach ($this->data['or'] as $itemValue)
                    {
                        $selectQuery .= "OR " . $itemValue[0] . " " . $itemValue[1] . " :bind_" . $i . " ";
                        $bind['bind_' . $i] = $itemValue[2];
                        $i++;
                    }
                }
                if (isset($this->data['and']))
                {
                    foreach ($this->data['and'] as $itemValue)
                    {
                        $selectQuery .= "AND " . $itemValue[0] . " " . $itemValue[1] . " :bind_" . $i . " ";
                        $bind['bind_' . $i] = $itemValue[2];
                        $i++;
                    }
                }
                $selectWhere = $db->prepare($selectQuery);
                $selectWhere->execute($bind);

                $result = ($type == 'object') ? $selectWhere->fetchAll(PDO::FETCH_CLASS, $this->called) : $selectWhere->fetchAll(PDO::FETCH_ASSOC);
                file_put_contents($file, '<?php $triton[\'where\'][\'execute\'][\'' . $file_name . '\'] = \'' . serialize($result) . '\';');
                if ($type == 'object')
                {
                    $model = new $this->called;
                    $model->setType('multi');
                    $model->setData($result);
                    return $model;
                }
                else
                {
                    return $result;
                }
            }
        }
        else
        {
            $db = $this->class::connectDatabase($this->database);
            $bind = [];
            $selectQuery = 'SELECT ' . $columns . ' FROM ' . $this->table . ' WHERE ' . $this->data['first'][0] . " " . $this->data['first'][2] . ":first ";
            $bind['first'] = $this->data['first'][1];
            $i = 0;
            if(isset($this->data['or']))
            {
                foreach($this->data['or'] as $itemValue)
                {
                    $selectQuery .= "OR " . $itemValue[0] . " " . $itemValue[1] . " :bind_" . $i . " ";
                    $bind['bind_' . $i] = $itemValue[2];
                    $i++;
                }
            }
            if(isset($this->data['and']))
            {
                foreach ($this->data['and'] as $itemValue)
                {
                    $selectQuery .= "AND " . $itemValue[0] . " " . $itemValue[1] . " :bind_" . $i . " ";
                    $bind['bind_' . $i] = $itemValue[2];
                    $i++;
                }
            }
            $selectWhere = $db->prepare($selectQuery);
            $selectWhere->execute($bind);

            $result = ($type == 'object') ? $selectWhere->fetchAll(PDO::FETCH_CLASS, $this->called) : $selectWhere->fetchAll(PDO::FETCH_ASSOC);
            file_put_contents($file, '<?php $triton[\'where\'][\'execute\'][\''. $file_name .'\'] = \'' . serialize($result) . '\';');
            if($type == 'object')
            {
                $model = new $this->called;
                $model->setType('multi');
                $model->setData($result);
                return $model;
            }
            else
            {
                return  $result;
            }

        }

        /*
        $bind = [];
        $selectQuery = "SELECT * FROM categories WHERE " . $this->where['first'][0] . " " . $this->where['first'][1] . ":first ";
        $bind['first'] = $this->where['first'][2];
        $i = 0;
        if(isset($this->where['orWhere']))
        {
            foreach($this->where['orWhere'] as $itemValue)
            {
                $selectQuery .= "OR " . $itemValue[0] . " " . $itemValue[1] . " :bind_" . $i . " ";
                $bind['bind_' . $i] = $itemValue[2];
                $i++;
            }
        }
        if(isset($this->where['andWhere']))
        {
            foreach ($this->where['andWhere'] as $itemValue)
            {
                $selectQuery .= "AND " . $itemValue[0] . " " . $itemValue[1] . " :bind_" . $i . " ";
                $bind['bind_' . $i] = $itemValue[2];
                $i++;
            }
        }
        $selectWhere = $pdoConnection->prepare($selectQuery);
        $selectWhere->execute($bind);

        if($selectWhere != false )
        {
            $selectWhere = $selectWhere->fetchAll();
        }
        else
        {
            return false;
        }
        if(count($selectWhere) > 1)
        {
            $classRow = new categoriesRow();
            foreach ($selectWhere as $itKey => $itValue)
            {
                foreach ($itValue as $itemKey => $itemValue)
                {
                    if($itemKey == self::$table_id_column)
                    {
                        $classRow->triton_categories_id = [$itemValue, self::$table_id_column];
                    }
                    if (!is_int($itemKey)) {
                        $classRow->$itemKey[] = $itemValue;
                    }
                }
            }
            return $classRow;
        }
        else if (count($selectWhere) == 1)
        {
            $classRow = new categoriesRow();
            foreach ($selectWhere[0] as $itemKey => $itemValue)
            {
                if($itemKey == self::$table_id_column)
                {
                    $classRow->triton_categories_id = [$itemValue, self::$table_id_column];
                }
                if (!is_int($itemKey)) {
                    $classRow->$itemKey[] = $itemValue;
                }
            }
            return $classRow;
        }
        else
        {
            return false;
        }
        */
    }

}