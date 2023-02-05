<?php

namespace Nieruchomosci\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Nieruchomosci\Form;
use Nieruchomosci\Model\Koszyk;
use Nieruchomosci\Model\Oferta;

class OfertyController extends AbstractActionController
{
    /**
     * OfertyController constructor.
     *
     * @param Oferta $oferta
     */
    public function __construct(public Oferta $oferta)
    {
    }

    public function listaAction()
    {
        $parametry = $this->params()->fromQuery();
        $strona = $parametry['strona'] ?? 1;

        // pobierz dane ofert
        $paginator = $this->oferta->pobierzWszystko($parametry);
        $paginator->setItemCountPerPage(10)->setCurrentPageNumber($strona);

        // zbuduj formularz wyszukiwania
        $form = new Form\OfertaSzukajForm();
        $form->populateValues($parametry);

        return new ViewModel([
            'form' => $form,
            'oferty' => $paginator,
            'parametry' => $parametry,
        ]);
    }

    public function szczegolyAction()
    {
        $form = new Form\OfertaSzukajForm();
        $daneOferty = $this->oferta->pobierz($this->params('id'));
        $form->populateValues($daneOferty);

        return ['oferta' => $daneOferty,
                'form' => $form, ];
    }

    public function drukujAction()
    {
        $oferta = $this->oferta->pobierz($this->params('id'));

        if ($oferta) {
            $this->oferta->drukuj($oferta);
        }

        return $this->getResponse();
    }

    public function drukujWszystkoAction()
    {
        
        $oferty = $this->oferta->pobierzDoDruku();
        if ($oferty) {
            $this->oferta->drukujWszystko($oferty);
        }
        return $this->getResponse();
    }
}
