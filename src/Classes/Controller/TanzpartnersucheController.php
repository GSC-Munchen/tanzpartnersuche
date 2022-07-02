<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use Symfony\Component\Mime\Address;
use GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche;


/**
 * This file is part of the "tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Martin Arend <tanzpartnersuche@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * TanzpartnersucheController
 */
class TanzpartnersucheController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * tanzpartnersucheRepository
     *
     * @var \GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository
     */
    protected $tanzpartnersucheRepository = null;

    /**
     * @param \GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository $tanzpartnersucheRepository
     */
    public function injectTanzpartnersucheRepository(\GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository $tanzpartnersucheRepository)
    {
        $this->tanzpartnersucheRepository = $tanzpartnersucheRepository;
    }

    /**
     * action index
     *
     * @return string|object|null|void
     */
    public function indexAction()
    {
    }

    /**
     * action show
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function showAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->view->assign('tanzpartnersuche', $tanzpartnersuche);
    }

    /**
     * action new
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche
     * @return string|object|null|void
     * 
     */
    public function newAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche = NULL)
    {
    }

    /**
     * action create
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche
     * @Extbase\Validate(param="newTanzpartnersuche" , validator="GSC\Tanzpartnersuche\Domain\Validator\NewTanzpartnersucheValidator")
     * @return void
     */
    public function createAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche)
    {
        
        // Accepting EU-DSGVO?

        // ToDo: in Formular einbauen
        //if (!$dsgv) {
        //    $this->addFlashMessage('Du musst den Bestimmungen zur Datenverwendung zustimmen, um Dein Profil anlegen zu können.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
        //    $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        //}

        // all checks passed - prepare next steps
        // generate verification code
        $verCode = date('y').rand(10,99).date('m').rand(10,99).date('d').rand(10,99).date('h').rand(10,99).date('i').rand(10,99).date('s').rand(10,99);
        $newTanzpartnersuche->setVerificationcode($verCode);

        // Password: salting and hashing
        $newTanzpartnersuche->setPassword(password_hash(($newTanzpartnersuche->getPassword()),PASSWORD_DEFAULT, array('cost' => 9)));
        $newTanzpartnersuche->setPasswordconfirmation('verified');

        // Hide User
        $newTanzpartnersuche->setHidden('1');

        // send out verification mail
        // assemble message
        $emailBody = "Hallo vom Gelb-Schwarz-Casino, \n";
        $emailBody .= "\n";
        $emailBody .= "Es wurde erfolgreich ein neuer Eintrag unter Verwendung dieser Mailadresse in unserer Tanzpartnersuche angelegt. \n";
        $emailBody .= "\n";
        $emailBody .= "Bitte bestätige Deine Email-Adresse in unserem System, indem Du auf \n";
        $emailBody .= "https://neu.gsc-muenchen.de/neu-hier/demo?tx_tanzpartnersuche_tanzpartnersuche%5Baction%5D=verify&tx_tanzpartnersuche_tanzpartnersuche%5Bcontroller%5D=Tanzpartnersuche&cHash=705d903e5c7fdef24a1077d0d6a9ee45 \n";
        $emailBody .= "klickst. \n";
        $emailBody .= "\n";
        $emailBody .= "Bitte gib dann Deinen Nutzernamen mit dem Du Dich registriert hast sowie den Verifikationscode ein. ";
        $emailBody .= "Erst danach wird Dein Eintrag in der Tanzpartnersuche sichtbar. \n";
        $emailBody .= "\n";
        $emailBody .= "Dein Verifikationscode lautet: \n";
        $emailBody .= $newTanzpartnersuche->getVerificationcode()." \n";
        $emailBody .= "\n";
        $emailBody .= "Solltest Du diese Registrierung nicht mehr wünschen oder sie nicht durchgeführt haben, so brauchst Du nichts weiter zu tun. ";
        $emailBody .= "Der Code ist maximal 48 Stunden gültig. Danach werden die eingegebenen Daten vollständig gelöscht und es ist eine neue Registrierung notwendig. \n";
        $emailBody .= "\n";
        $emailBody .= "Falls Du Fragen hast, kannst Du Dich gerne an tanzpartner@gsc-muenchen.de wenden. Wir versuchen diese so schnell wie möglich zu beantworten. \n";
        $emailBody .= "\n";
        $emailBody .= "Vielen Dank für die Nutzung unserer Tanzpartnersuche und viel Erfolg dabei! \n";
        $emailBody .= "Dein Gelb-Schwarz Casino München e.V. \n";
        $emailBody .= "\n";
        $emailBody .= "---\n";
        $emailBody .= "Vertreten durch den Präsidenten Stefan Göttlinger \n";
        $emailBody .= "Registergericht: München \n";
        $emailBody .= "Registernummer: VR 4385\n";
        $emailBody .= "https://www.gsc-muenchen.de/impressum";

        // send mail
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
        $mail->to(new Address($newTanzpartnersuche->getEmail(), $newTanzpartnersuche->getEmail()));
        $mail->subject('Bitte verifiziere Deinen Eintrag in der Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
        $mail->text($emailBody);
        $mail->send();
        
        // Add new profile to database
        $this->addFlashMessage('Der Eintrag wurde erfolgreich angelegt und die Mail mit dem Verifikationscode versendet.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->add($newTanzpartnersuche);

        // Display overall result on verification page
        $this->redirect('verify');
    }

    /**
     * action verify
     * 
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $verifyTanzpartnersuche
     * @return string|object|null|void
     */
    public function verifyAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $verifyTanzpartnersuche = NULL)
    {
    }

    /**
     * action status
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $verifyTanzpartnersuche
     * @Extbase\Validate(param="verifyTanzpartnersuche" , validator="GSC\Tanzpartnersuche\Domain\Validator\VerifyTanzpartnersucheValidator")
     * @return void
     * 
     */
    public function statusAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $verifyTanzpartnersuche)
    {
        $this->addFlashMessage('Der Eintrag wurde erfolgreich freigeschaltet. Viel Erfolg bei Deiner Tanzpartnersuche!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
    }

    /**
     * action edit
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("tanzpartnersuche")
     * @return string|object|null|void
     */
    public function editAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->view->assign('tanzpartnersuche', $tanzpartnersuche);
    }

    /**
     * action update
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function updateAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->update($tanzpartnersuche);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function deleteAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->remove($tanzpartnersuche);
        $this->redirect('list');
    }

    /**
     * action search
     *
     * @return string|object|null|void
     */
    public function searchAction()
    {
    }

    /**
     * action help
     *
     * @return string|object|null|void
     */
    public function helpAction()
    {
    }

    /**
     * action detail
     *
     * @return string|object|null|void
     */
    public function detailAction()
    {
    }

    /**
     * action login
     *
     * @return string|object|null|void
     */
    public function loginAction()
    {
    }
}
