<?php

namespace Nieruchomosci\Model;

use ArrayObject;
use Laminas\Db\Adapter as DbAdapter;
use Laminas\Db\Sql\Sql;
use Laminas\Filter\ToInt;
use Laminas\Paginator\Adapter\LaminasDb\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\Session\SessionManager;
use Mpdf\Mpdf;

class Oferta implements DbAdapter\AdapterAwareInterface
{
    use DbAdapter\AdapterAwareTrait;

    public function __construct(public PhpRenderer $phpRenderer)
    {
    }
    
    /**
     * Pobiera obiekt Paginator dla przekazanych parametrów.
     *
     * @param array $szukaj
     * @return \Laminas\Paginator\Paginator
     */
    public function pobierzWszystko(array $szukaj = []): Paginator
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $select = $sql->select('oferty');

        if (!empty($szukaj['typ_oferty'])) {
            $select->where(['typ_oferty' => $szukaj['typ_oferty']]);
        }
        if (!empty($szukaj['typ_nieruchomosci'])) {
            $select->where(['typ_nieruchomosci' => $szukaj['typ_nieruchomosci']]);
        }
        if (!empty($szukaj['numer'])) {
            $select->where(['numer' => $szukaj['numer']]);
        }
        if (!empty($szukaj['powierzchniaMin'])) {
            $select->where->greaterThanOrEqualTo('powierzchnia', $szukaj['powierzchniaMin']);
        }
        if (!empty($szukaj['powierzchniaMax'])) {
            $select->where->lessThanOrEqualTo('powierzchnia', $szukaj['powierzchniaMax']);
        }
        if (!empty($szukaj['cenaMin'])) {
            $select->where->greaterThanOrEqualTo('cena', $szukaj['cenaMin']);
        }
        if (!empty($szukaj['cenaMax'])) {
            $select->where->lessThanOrEqualTo('cena', $szukaj['cenaMax']);
        }

        $paginatorAdapter = new DbSelect($select, $dbAdapter);

        return new Paginator($paginatorAdapter);
    }

    public function pobierzDoDruku() 
    {
        $dbAdapter = $this->adapter;
        $session = new SessionManager();

        $sql = new Sql($dbAdapter);
        $select = $sql->select('oferty');
        $select->join(
            'koszyk',
            'oferty.id = koszyk.id_oferty',
            [],
            $select::JOIN_INNER
        );
        $select->where(['koszyk.id_sesji' => $session->getId()]);
        $selectString = $sql->buildSqlString($select);
		$wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        return $wynik;
    }

    /**
     * Pobiera dane jednej oferty.
     *
     * @param int $id
     * @return arrayObject
     * 
     */
    public function pobierz(int $id) : ArrayObject
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $select = $sql->select('oferty');
        $select->where(['id' => $id]);
        $selectString = $sql->buildSqlString($select);
        $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        return $wynik->count() ? $wynik->current() : [];
    }

    /**
     * Generuje PDF z danymi oferty.
     *
     * @param $oferta
     * @throws \Mpdf\MpdfException
     */
    public function drukuj($oferta): void
    {
        $vm = new ViewModel(['oferta' => $oferta]);
        $vm->setTemplate('nieruchomosci/oferty/drukuj');
        $html = $this->phpRenderer->render($vm);

        $mpdf = new Mpdf(['tempDir' => getcwd() . '/data/temp']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('oferta.pdf', 'D');
    }

    public function drukujDoZmiennej($oferta)
    {
        $vm = new ViewModel(['oferta' => $oferta]);
        $vm->setTemplate('nieruchomosci/oferty/drukuj');
        $html = $this->phpRenderer->render($vm);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('oferta.pdf', \Mpdf\Output\Destination::STRING_RETURN);
    }

    public function drukujWszystko($oferty): void
    {
        
        $mpdf = new Mpdf(['tempDir' => getcwd() . '/data/temp']);
        foreach($oferty as $oferta):
            $vm = new ViewModel(['oferta' => $oferta]);
            $vm->setTemplate('nieruchomosci/oferty/drukuj');
            $html = $this->phpRenderer->render($vm);

            
            $mpdf->AddPage();
            $mpdf->WriteHTML($html);
        endforeach;

        if($mpdf != null)
        {
            $mpdf->Output('koszyk.pdf', 'D');
        }
    
    }

    public function service($idOferty, $tresc, $telefon, $nadawca): void
    {
        $dbAdapter = $this->adapter;
		$session = new SessionManager();
		$sql = new Sql($dbAdapter);

        $insert = $sql->insert('log');
		$insert->values([
			'id_klienta' => $session->getId(),
            'id_oferty' => $idOferty,
            'tresc' => $tresc,
            'telefon' => $telefon,
            'nadawca' => $nadawca
		]);
			
		$selectString = $sql->buildSqlString($insert);
		$wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }
}
