<?php

namespace Nieruchomosci\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Nieruchomosci\Form;
use Nieruchomosci\Model\Koszyk;

class KoszykController extends AbstractActionController
{
    /**
     * KoszykController constructor.
     *
     * @param Koszyk $koszyk
     */
    public function __construct(Koszyk $koszyk)
    {
        $this->koszyk = $koszyk;
    }

    public function dodajAction()
    {
        if ($this->getRequest()->isPost()) {
            if($this->koszyk->dodaj($this->params('id')) != null) 
            {
                $this->getResponse()->setContent('ok');
            }
        }
        return $this->getResponse();
    }

    public function usunAction()
    {
        if ($this->getRequest()->isPost()) {
            if($this->koszyk->usun($this->params('id')) != null) 
            {
                $this->getResponse()->setContent('ok');
            }
        }
        return $this->getResponse();
    }

    public function listaAction()
    {
        $parametry = $this->params()->fromQuery();
        $strona = $parametry['strona'] ?? 1;

        // pobierz dane ofert
        $paginator = $this->koszyk->pobierzWszystko($parametry);
        $paginator->setItemCountPerPage(30)->setCurrentPageNumber($strona);

        // zbuduj formularz wyszukiwania
        $form = new Form\OfertaSzukajForm();
        $form->populateValues($parametry);

        return new ViewModel([
            'form' => $form,
            'oferty' => $paginator,
            'parametry' => $parametry,
        ]);
    }
}
