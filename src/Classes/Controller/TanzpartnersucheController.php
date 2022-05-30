<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;


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
     */
    public function newAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche = NULL)
    {
    }

    /**
     * action new_step1
     *
     * @return string|object|null|void
     */
    public function new_step1Action()
    {
    }

    /**
     * action new_step2
     *
     * @param string $username
     * @param string $email
     * @return string|object|null|void
     */
    public function new_step2Action($username,$email)
    {
        // Form Validations
        
        // E-Mail already in use?
        if ($this->tanzpartnersucheRepository->findUserByEmail($email) != NULL) {
            $this->addFlashMessage('Diese E-Mail wurde bereits registriert. Bitte eine andere verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new_step1',null,null,null);
        }

        // Valid format of E-Mail?
        $s = '/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i';
        if(!preg_match($s, $email)) {
            $this->addFlashMessage('Keine gültige E-Mail. Bitte prüfe das Format.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new_step1',null,null,null);
        }

        // all checks passed - prepare next steps
        // generate verification code
        $verCode = date('y').rand(10,99).date('m').rand(10,99).date('d').rand(10,99).date('h').rand(10,99).date('i').rand(10,99).date('s').rand(10,99);
        //$newTanzpartnersuche->setVerificationcode($verCode);

        // Initalise new User
        //$newTanzpartnersuche->setHidden('1');

        // send out verification mail
        // To-Do E-Mail versenden!

        // Add to database
        //$this->tanzpartnersucheRepository->add($newTanzpartnersuche);

    }

    /**
     * action new_step3
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche
     * @return string|object|null|void
     */
    public function new_step3Action(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche = NULL)
    {
    }

    /**
     * action create
     *
     * @param string $password2
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche
     * @return string|object|null|void
     */
    public function createAction($password2,\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche)
    {
        // Form Validations
        
        // Username already in use?
        if ($this->tanzpartnersucheRepository->findUserByUsername($newTanzpartnersuche->getUsername()) != NULL) {
            $this->addFlashMessage('Dieser Benutzername wurde bereits registriert. Bitte einen anderen verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
            // $this->redirect('new', null, null, array($this=>$newTanzpartnersuche));
        }

        // Username < 3 characters?
        if (strlen($newTanzpartnersuche->getUsername())<3) {
            $this->addFlashMessage('Username muss mindestens 3 Zeichen lang sein.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        }

        // Password < 8 characters?
        if (strlen($newTanzpartnersuche->getPassword())<8) {
            $this->addFlashMessage('Passwort muss mindestens 8 Zeichen lang sein.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        }

        // Password empty?
        if (empty($newTanzpartnersuche->getPassword())) {
            $this->addFlashMessage('Kein Passwort eingegeben. Bitte korrigieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
          }
        
        // Identical passwords?
        if ($newTanzpartnersuche->getPassword() != $password2 ) {
            $this->addFlashMessage('Die Passwörter sind unterschiedlich. Bitte korrigieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        }

        // E-Mail already in use?
        if ($this->tanzpartnersucheRepository->findUserByEmail($newTanzpartnersuche->getEmail()) != NULL) {
            $this->addFlashMessage('Diese E-Mail wurde bereits registriert. Bitte eine andere verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        }

        // Valid format of E-Mail?
        $s = '/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i';
        if(!preg_match($s, $newTanzpartnersuche->getEmail())) {
            $this->addFlashMessage('Keine gültige E-Mail. Bitte prüfe das Format.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        }

        // Age < 18?
        if ($newTanzpartnersuche->getAge()<18) {
            $this->addFlashMessage('Du musst mindestens 18 Jahre alt sein um die Tanzpartnersuche nutzen zu können.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->forward('new',null,null,array('tanzpartnersuche'=>$newTanzpartnersuche));
        }

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

        // Hide User
        $newTanzpartnersuche->setHidden('1');
        
        // Add to database
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->add($newTanzpartnersuche);

        // send out verification mail
        $status = $this->tanzpartnersucheRepository->sendVeriMail($newTanzpartnersuche->getEmail(), $verCode, $newTanzpartnersuche->getUsername());
        
        //ToDo: Move Code to repository
        if ($status = '1') {
            $this->addFlashMessage('Mail erfolgreich verschickt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);

            // Mail an Admin versenden
            $mailSubject = "Tanzpartnersuche des GSC München e.V. - Neuer Eintrag: ".$newTanzpartnersuche->getUsername();
            $emailBody = "Ein neuer Eintrag wurde angelegt. \n";
            $emailBody .= "\n";
            $emailBody .= "Der Nutzer ".$newTanzpartnersuche->getUsername()." hat seinen Eintrag in der Tanzpartnersuche angelegt und den Freischaltungslink erhalten.\n";
            $emailBody .= "\n";
            $emailBody .= "Nutzername: ".$newTanzpartnersuche->getUsername()." \n";
            $emailBody .= "E-Mail:     ".$newTanzpartnersuche->getEmail()." \n";
            $emailBody .= "Profil:     ".$newTanzpartnersuche->getBio()." \n";
            $emailBody .= "\n";
            $emailBody .= "Ende der Nachricht\n";

            $message = GeneralUtility::makeInstance(MailMessage::class);
            $message->setTo("tanzpartner@gsc-muenchen.de")
                    ->setFrom("tanzpartner@gsc-muenchen.de")
                    ->setSubject($mailSubject);

            $message->html($emailBody);
            
        }
        
        // Display overall result on status page
        $this->redirect('status');
    }

    /**
     * action status
     *
     * @return string|object|null|void
     */
    public function statusAction()
    {
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
     * action verify
     *
     * @return string|object|null|void
     */
    public function verifyAction()
    {
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
