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
        foreach ($this->variables as $key => $value)
        {

        }
    }

    function __debugInfo()
    {
        return $this->variables;
    }

}