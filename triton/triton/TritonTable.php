<?php
class TritonTable
{
  public $variables = [],
         $settings = [
           'engine' => 'ENGINE=InnoDB',
           'DEFAULT CHARSET utf8 DEFAULT COLLATE utf8_general_ci'
         ];

  public function increments($str)
  {
    if (is_object($str))
    {
      echo 'O : ' . $str;
    }
    else
    {
      echo 'D : ' . $str;
    }
  }
}
