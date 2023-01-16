<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model\Data;
use Application\Model\Miesiace;
use Application\Model\Liczby;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function dataAction()
    {
        $data = new Data();

        return new ViewModel([
            'dzisiaj' => $data->dzisiaj(),
            'dni_tygodnia' => $data->dniTygodnia(),
        ]);
    }

    public function miesiaceAction() 
    {
        $months = new Miesiace();

        return new ViewModel([
            'miesiace' => $months->pobierzWszystkie(),
        ]);
    }

    public function liczbyAction() 
    {
        $numbers = new Liczby();

        return new ViewModel([
            'liczby' => $numbers->generuj(),
        ]);
    }
}
