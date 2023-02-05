<?php

namespace Nieruchomosci\Model;

use Laminas\Db\Adapter as DbAdapter;
use Laminas\Db\Sql\Sql;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Laminas\Paginator\Adapter\LaminasDb\DbSelect;
use Laminas\Paginator\Paginator;

class Koszyk implements DbAdapter\AdapterAwareInterface
{
	use DbAdapter\AdapterAwareTrait;
	
	protected Container $sesja;
	
	public function __construct()
	{
		$this->sesja = new Container('koszyk');
		$this->sesja->liczba_ofert = $this->sesja->liczba_ofert ?: 0;
	}

    /**
     * Dodaje ofertdo koszyka.
     *
     * @param int $idOferty
     * @return int|null
     */
	public function dodaj(int $idOferty): ?int
	{
		$dbAdapter = $this->adapter;
		$session = new SessionManager();
		$sql = new Sql($dbAdapter);

		//wyszukiwanie czy dla danej sesji istnieje już dany klucz, zabezpieczenie
        $select = $sql->select('koszyk');
        $select->where(['id_sesji' => $session->getId()]);
        $select->where(['id_oferty' => $idOferty]);
		$selectString = $sql->buildSqlString($select);
        $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

		if($wynik->count() != 1) {
			$insert = $sql->insert('koszyk');
			$insert->values([
				'id_oferty' => $idOferty,
				'id_sesji' => $session->getId()
			]);
			
			$selectString = $sql->buildSqlString($insert);
			$wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

			//aktualizacja liczby ofert ze wzgledu na ilosc wynikow w bazie danych
			$countCart = $sql->select('koszyk');
			$countCart->where(['id_sesji' => $session->getId()]);
			$selectString = $sql->buildSqlString($countCart);
			$countCart = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
			$this->sesja->liczba_ofert = $countCart->count();
			
			try {
				return $wynik->getGeneratedValue();
			} catch(\Exception $e) {
				return null;
			}
		} else {
			return null;
		}
	}

    public function usun(int $idOferty)
    {
        $dbAdapter = $this->adapter;
		$session = new SessionManager();
		$sql = new Sql($dbAdapter);

        $delete = $sql->delete('koszyk');
        $delete->where(['id_sesji' => $session->getId()]);
        $delete->where(['id_oferty' => $idOferty]);

        $selectString = $sql->buildSqlString($delete);
		$wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        try {
            return $wynik->getGeneratedValue();
        } catch(\Exception $e) {
            return null;
        }
    }

	public function pobierzWszystko(array $szukaj = []): Paginator
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

    /**
     * Zwraca liczbe ofert w koszyku.
     *
     * @return int
     */
	public function liczbaOfert(): int
	{
        $dbAdapter = $this->adapter;
		$session = new SessionManager();
		$sql = new Sql($dbAdapter);
        $countCart = $sql->select('koszyk');
			$countCart->where(['id_sesji' => $session->getId()]);
			$selectString = $sql->buildSqlString($countCart);
			$countCart = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
			$this->sesja->liczba_ofert = $countCart->count();
		return $this->sesja->liczba_ofert;
	}
}