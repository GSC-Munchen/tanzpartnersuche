<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;


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
     * @return string|object|null|void
     */
    public function newAction()
    {
    }

    /**
     * action create
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche
     * @return string|object|null|void
     */
    public function createAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche)
    {
        // Form Validations
        
        // Username already in use?
        if ($this->tanzpartnersucheRepository->findUserByUsername($newTanzpartnersuche->getUsername()) != NULL) {
            $this->addFlashMessage('Dieser Benutzername wurde bereits registriert. Bitte einen anderen verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            // $this->forward(addForm,null,null,array('tanzpartnersuche'=>$tanzpartnersuche));
            $this->redirect('new', null, null, array($this=>$newTanzpartnersuche));
        }

        // Password empty?
        if (empty($newTanzpartnersuche->getPassword())) {
            $this->addFlashMessage('Kein Passwort eingegeben. Bitte korrigieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->redirect('new', null, null, array($this=>$newTanzpartnersuche));
          }
        
        // Passwords are the same?
        //if ($newTanzpartnersuche->getPassword() != $password2 ) {
        //    $this->addFlashMessage('Die Passwörter sind unterschiedlich. Bitte korrigieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
        //    // $this->forward('new', null, null, array('tanzpartnersuche'=>$newTanzpartnersuche));
        //    $this->redirect('new', null, null, array($this=>$newTanzpartnersuche));
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

        // ToDo: Gender wird nicht eingetragen, weitere Abfragen, Redirekt/Forward mit Übernahme der Daten

        // redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL, $pageUid = NULL, $delay = 0, $statusCode = 303)
        // forward($actionName, $controllerName = NULL, $extensionName = NULL,array $arguments = NULL)

        // send out verification mail
        $status = $this->tanzpartnersucheRepository->sendVeriMail($newTanzpartnersuche->getEmail(), $verCode, $newTanzpartnersuche->getUsername());
        if ($status = '1') {
            $this->addFlashMessage('Mail erfolgreich verschickt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);

            // Mail an Admin versenden
            //$mailSubject = "Tanzpartnersuche des GSC München e.V. - Neuer Eintrag: ".$tanzpartnersuche->getUsername();
            $emailBody = "Ein neuer Eintrag wurde angelegt. \n";
            $emailBody .= "\n";
            //$emailBody .= "Der Nutzer ".$tanzpartnersuche->getUsername()." hat seinen Eintrag in der Tanzpartnersuche angelegt und den Freischaltungslink erhalten.\n";
            $emailBody .= "\n";
            //$emailBody .= "Nutzername: ".$tanzpartnersuche->getUsername()." \n";
            //$emailBody .= "E-Mail:     ".$tanzpartnersuche->getEmail()." \n";
            //$emailBody .= "Profil:     ".$tanzpartnersuche->getProfile()." \n";
            $emailBody .= "\n";
            $emailBody .= "Ende der Nachricht\n";

            //$message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
            //$message->setTo("tanzpartner@gsc-muenchen.de")
            //        ->setFrom("tanzpartner@gsc-muenchen.de")
            //        ->setSubject($mailSubject);

            //$message->setBody($emailBody, 'text/plain');
            //$message->send();

        }
        
        
        
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
