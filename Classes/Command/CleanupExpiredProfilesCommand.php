<?php

namespace Gsc\Tanzpartnersuche\Command;

use Gsc\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Löscht Profile endgültig aus der Datenbank, deren letzte Änderung mehr als 9 Monate
 * zurückliegt. Profile sind ab 6 Monaten nach der letzten Änderung im Frontend nicht mehr
 * sichtbar (siehe Tanzpartnersuche::getVisibleUntil()); ein Login in diesem Zeitraum
 * aktualisiert "changed" und schaltet das Profil für weitere 6 Monate frei. Erfolgt bis
 * 9 Monate nach der letzten Änderung kein Login, wird der Datensatz hier unwiderruflich
 * gelöscht (Hard-Delete, nicht nur "deleted"-Flag).
 */
#[AsCommand(
    name: 'tanzpartnersuche:cleanup-expired-profiles',
    description: 'Löscht Profile endgültig, die seit 9 Monaten nicht mehr geändert wurden.',
)]
class CleanupExpiredProfilesCommand extends Command
{
    public function __construct(
        private readonly TanzpartnersucheRepository $repository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $this->repository->removeExpiredProfiles();

        $output->writeln(sprintf('%d Profil(e) endgültig gelöscht (letzte Änderung vor mehr als 9 Monaten).', $count));

        return Command::SUCCESS;
    }
}
