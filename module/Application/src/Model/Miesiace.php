<?php

namespace Application\Model;

class Miesiace
{
    public function pobierzWszystkie() 
    {
        $months = array (
            array("Styczeń", "black"),
            array("Luty", "magenta"),
            array("Marzec", "yellow"),
            array("Kwiecień", "pink"),
            array("Maj", "navy"),
            array("Czerwiec", "red"),
            array("Lipiec", "blue"),
            array("Sierpień", "indigo"),
            array("Wrzesień", "green"),
            array("Październik", "purple"),
            array("Listopad", "violet"),
            array("Grudzień", "orange")
          );

        return $months;
    }
}