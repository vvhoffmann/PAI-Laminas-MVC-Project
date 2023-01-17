<?php

namespace Application\Model;

class Liczby
{
    public function generuj()
    {
        
        $even = array();
        $odd = array();
        for($i = 0 ; $i < 100 ; ++$i)
        {
            $number = rand(0,1000);
            if($number % 2 == 0) 
            {
                array_push($even, $number);
            } else
            {
                array_push($odd, $number);
            }
        }
        sort($even);
        sort($odd);
        $numbers = array($even, $odd);
        return $numbers;
    }
}
