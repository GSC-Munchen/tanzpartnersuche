<?php

namespace Gsc\Tanzpartnersuche\Domain\Repository;

use Gsc\Tanzpartnersuche\Domain\Model\Tanzpartnersuche;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class TanzpartnersucheRepository extends Repository
{
    /**
     * Löscht einen Account unwiderruflich aus der Datenbank (Hard-Delete),
     * statt ihn nur über das TCA-Feld "deleted" zu verstecken. Der Nutzer
     * hat beim Löschen ausdrücklich zugesichert bekommen, dass seine Daten
     * sofort und endgültig entfernt werden.
     */
    public function removePermanently(Tanzpartnersuche $user): void
    {
        $uid = $user->getUid();
        if ($uid === null) {
            return;
        }

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable('tx_tanzpartnersuche_domain_model_tanzpartnersuche');
        $connection->delete('tx_tanzpartnersuche_domain_model_tanzpartnersuche', ['uid' => $uid]);
    }
    /**
     * Sucht nach einem bestehenden Eintrag anhand der E-Mail-Adresse, inkl. noch
     * versteckter (unverifizierter) Datensätze. Die E-Mail-Adresse ist der einzige
     * eindeutige Identifikator eines Profils (der Username kann mehrfach vergeben
     * werden) und wird daher für Registrierung, Verifikation, Login und
     * Passwort-Reset einheitlich zur Ermittlung des Datensatzes verwendet.
     */
    public function findOneByEmail(string $email): ?Tanzpartnersuche
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('email', $email));

        /** @var Tanzpartnersuche|null $result */
        $result = $query->execute()->getFirst();
        return $result;
    }

    /**
     * Liefert einen einzelnen Eintrag anhand der UID, inkl. noch versteckter
     * (unverifizierter) Datensätze, für die Detailansicht im Backend.
     */
    public function findOneByUidIncludingHidden(int $uid): ?Tanzpartnersuche
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('uid', $uid));

        /** @var Tanzpartnersuche|null $result */
        $result = $query->execute()->getFirst();
        return $result;
    }

    /**
     * Liefert alle Einträge inkl. noch versteckter (unverifizierter) Datensätze,
     * für die Übersicht im TYPO3-Backend.
     */
    public function findAllIncludingHidden(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->setOrderings(['created' => QueryInterface::ORDER_DESCENDING]);

        return $query->execute();
    }

    /**
     * Liefert nur sichtbare (verifizierte) Profile, gefiltert nach den Suchkriterien.
     * Reziprokes Matching: der Kandidat muss das gesuchte Geschlecht haben
     * (candidate.gender == searchGender) und selbst jemanden meines Geschlechts suchen
     * (candidate.role == myGender). category/levels sind optionale Zusatzfilter.
     *
     * @param int[] $levels
     */
    public function findBySearchCriteria(?int $myGender, ?int $searchGender, ?int $category, array $levels): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        $visibleSince = (new \DateTime('-6 months'))->getTimestamp();
        $constraints[] = $query->greaterThanOrEqual('changed', $visibleSince);

        if ($searchGender !== null) {
            $constraints[] = $query->equals('gender', $searchGender);
        }
        if ($myGender !== null) {
            $constraints[] = $query->equals('role', $myGender);
        }
        if ($category !== null) {
            $constraints[] = $query->equals('category', $category);
        }
        if ($levels !== []) {
            $constraints[] = $query->in('level', $levels);
        }

        if ($constraints === []) {
            return $query->matching($query->equals('uid', 0))->execute();
        }

        $query->matching($query->logicalAnd(...$constraints));
        return $query->execute();
    }

    /**
     * Liefert alle Profile (inkl. hidden, unabhängig von der Storage-Page), deren letzte
     * Änderung vor dem übergebenen Zeitpunkt liegt. Grundlage für den endgültigen Hard-Delete
     * inaktiver Profile nach 9 Monaten (siehe CleanupExpiredProfilesCommand).
     */
    public function findOlderThan(\DateTimeImmutable $threshold): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->lessThan('changed', $threshold->getTimestamp()));

        return $query->execute();
    }

    /**
     * Löscht alle Profile endgültig, deren letzte Änderung mehr als 9 Monate zurückliegt
     * (Hard-Delete, nicht nur "deleted"-Flag). Wird sowohl vom CleanupExpiredProfilesCommand
     * (Scheduler) als auch bei jeder Neuregistrierung im Frontend aufgerufen (siehe
     * TanzpartnersucheController::createAction()), damit die Bereinigung auch dann läuft,
     * wenn kein Scheduler-Task eingerichtet ist.
     */
    public function removeExpiredProfiles(): int
    {
        $threshold = new \DateTimeImmutable('-9 months');
        $expiredProfiles = $this->findOlderThan($threshold);

        $count = 0;
        foreach ($expiredProfiles as $profile) {
            $this->removePermanently($profile);
            $count++;
        }

        return $count;
    }
}