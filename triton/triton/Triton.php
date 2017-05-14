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

}