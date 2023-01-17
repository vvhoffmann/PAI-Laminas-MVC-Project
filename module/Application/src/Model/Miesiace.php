<?php

namespace Application\Model;

class Miesiace
{
    public function pobierzWszystkie() 
    {
        $months = array (
            array("Styczeń", "blue"),
            array("Luty", "green"),
            array("Marzec", "yellow"),
            array("Kwiecień", "red"),
            array("Maj", "pink"),
            array("Czerwiec", "white"),
            array("Lipiec", "black"),
            array("Sierpień", "indigo"),
            array("Wrzesień", "violet"),
            array("Październik", "purple"),
            array("Listopad", "orange"),
            array("Grudzień", "brown")
          );

        return $months;
    }
}
