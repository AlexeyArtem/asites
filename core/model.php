<?php

class ResourceNotFoundException extends Exception
{

}

class Model
{
    
}

function getCount($array) : int
{
    if(empty($array)) return 0;
    else return count($array);
}