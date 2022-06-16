<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Domain\Repository;
use TYPO3\CMS\Extbase\Persistence\Repository;


/**
 * This file is part of the "tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Martin Arend <tanzpartnersuche@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * The repository for Tanzpartnersuches
 */
class TanzpartnersucheRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

/**
     * 
     * @param string $checkUsername
     * @return QueryResultInterface|array
     * @api
     */
    public function findUserByUsername($checkUsername) 
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('username',$checkUsername),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute();

        // Wenn Suche ohne Treffer, Ergebniswert auf NULL setzen
        if (count($result)=='0') 
            $result = NULL;

        return $result;
    }

    /**
     * 
     * @param string $checkmail
     * @return QueryResultInterface|array
     * @api
     */
    public function findUserByEmail($checkmail) 
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('email',$checkmail),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute();

        // Wenn Suche ohne Treffer, Ergebniswert auf NULL setzen
        if (count($result)=='0') 
            $result = NULL;

        return $result;
    }

    /**
     * 
     * @param string $mailRecipient
     * @param string $mailCode
     * @param string $mailName
     */
    public function sendVeriMail($mailRecipient,$mailCode,$mailName) 
    {
        // Versenden einer Mail mit Verifikationscode        
        $mailSender = "tanzpartner@gsc-muenchen.de";
        $mailSubject = "Tanzpartnersuche des GSC München e.V. - Freischaltung Deines Eintrages";
        $emailBody = "Hallo ".$mailName.", \n";
        $emailBody .= "\n";
        $emailBody .= "vielen Dank für das Anlegen Deines Eintrages in der Tanzpartnersuche des Gelb-Schwarz-Casino München e.V. \n";
        $emailBody .= "Bitte schalte diesen nun noch frei. Klicke dazu auf den folgenden Link:\n";
        $emailBody .= "https://www.gsc-muenchen.de/neu-hier/tanzpartnersuche/?tx_tanzpartnersuche_tanzpartnersuche[action]=verify";
        $emailBody .= "Alternativ kopiere diesen bitte und füge ihn direkt in Deinen Browser ein.\n";
        $emailBody .= "\n";
        $emailBody .= "Bitte gib die Mailadresse ein, mit der Du Dich registriert hast.";
        $emailBody .= "Als Authentisierungscode verwende bitte: ".$mailCode;
        $emailBody .= "\n";
        $emailBody .= "Solltest Du den Eintrag nicht freischalten wollen, oder diese E-Mail unberechtigterweise erhalten haben, dann kannst Du die Mail ignorieren.\n";
        $emailBody .= "In diesem Fall bitten wir um Verzeihung für die entstandenen Umstände. Die eingebenen Daten werden nach etwa 48 Stunden vollständig gelöscht.\n";
        $emailBody .= "\n";
        $emailBody .= "Besten Dank für die Nutzung der Tanzpartnersuche und viel Erfolg!\n";
        $emailBody .= "\n";
        $emailBody .= "Gelb-Schwarz-Casino München e.V.\n";
        $emailBody .= "Sonnenstraße 12a / II\n";
        $emailBody .= "D-80331 München\n";
        $emailBody .= "\n";
        $emailBody .= "Telefon Büro: 089 / 548 299 30\n";
        $emailBody .= "Telefon Clubheim: 089 / 548 299 33\n";
        $emailBody .= "Fax: 089 / 548 299 31\n";
        $emailBody .= "\n";
        $emailBody .= "Bitte beachtet, dass die Telefone nicht durchgehend besetzt sind. Bevorzugt wird daher die Kontaktaufnahme mittels Fax oder E-Mail.\n";
        $emailBody .= "\n";
        $emailBody .= "Registergericht: München\n";
        $emailBody .= "Registernummer: VR 4385 \n";

        //$message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        //$message->setTo($mailRecipient)
        //        ->setFrom($mailSender)
        //        ->setSubject($mailSubject);

        //$message->setBody($emailBody, 'text/plain');
        //$message->send();
        //return $message->isSent();
    }

}
