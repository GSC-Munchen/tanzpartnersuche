<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;


/**
 * This file is part of the "Tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Peter-Benedikt von Niebelschütz <ias@gsc-muenchen.de>, GSC München e.V.
 *          Martin Arend <ias@gsc-muenchen.de>, GSC München e.V.
 */


/**
 * Debugger
 */
use TYPO3\CMS\Core\Utility\DebugUtility;

/**
 * UserController
 */
class UserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * userRepository
     *
     * @var \GSC\Tanzpartnersuche\Domain\Repository\UserRepository
     */
    protected $userRepository = null;

    /**
     * action index
     *
     * @return string|object|null|void
     */
    public function indexAction()
    {
    }

    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $users = $this->userRepository->findAll();
        $this->view->assign('users', $users);
    }

    /**
     * action show
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function showAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action new
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $newUser
     * @return string|object|null|void
     */
    public function newAction(\GSC\Tanzpartnersuche\Domain\Model\User $newUser = NULL)
    {
        $this->view->assign('user', $newUser);
    }

    /**
     * action create
     *
     * @param string $password2
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $newUser
     * @return string|object|null|void
     */
    public function createAction($password2,\GSC\Tanzpartnersuche\Domain\Model\User $newUser)
    {
        // Form Validations
        
        // Username already in use?
        if ($this->userRepository->findUserByUsername($newUser->getUsername()) != NULL) {
            $this->addFlashMessage('Dieser Benutzername wurde bereits registriert. Bitte einen anderen verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            // $this->forward(addForm,null,null,array('tanzpartnersuche'=>$tanzpartnersuche));
            $this->redirect('new', null, null, array($this=>$newUser));
        }

        // Password empty?
        if (empty($newUser->getPassword())) {
            $this->addFlashMessage('Kein Passwort eingegeben. Bitte korrigieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            $this->redirect('new', null, null, array($this=>$newUser));
          }
        
        // Passwords are the same?
        if ($newUser->getPassword() != $password2 ) {
            $this->addFlashMessage('Die Passwörter sind unterschiedlich. Bitte korrigieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
            // $this->redirect('list');
            // $this->forward('new', null, null, array('tanzpartnersuche'=>$newUser));
            $this->redirect('new', null, null, array($this=>$newUser));
        }

        // all checks passed - prepare next steps
        // generate verification code
        $verCode = date('y').rand(10,99).date('m').rand(10,99).date('d').rand(10,99).date('h').rand(10,99).date('i').rand(10,99).date('s').rand(10,99);
        // ToDo: Aktiviere und nehme es in Datenmodell mit auf: 
        // $newUser->setVerificationcode($verCode);

        // Password: salting and hashing
        $newUser->setPassword(password_hash(($newUser->getPassword()),PASSWORD_DEFAULT, array('cost' => 9)));

        // Hide User
        $newUser->setHidden('1');
        

        // ToDo: Gender wird nicht eingetragen, weitere Abfragen, Redirekt/Forward mit Übernahme der Daten
        


        // redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL, $pageUid = NULL, $delay = 0, $statusCode = 303)
        // forward($actionName, $controllerName = NULL, $extensionName = NULL,array $arguments = NULL)

        // add $newUser to database
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->add($newUser);
        $this->redirect('list');

        // send out verification mail
        $status = $this->tanzpartnersucheRepository->sendVeriMail($newUser->getEmail(), $verCode, $newUser->getUsername());
        if ($status = '1') {
            $this->addFlashMessage('Mail erfolgreich verschickt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);

            // Mail an Admin versenden
            $mailSubject = "Tanzpartnersuche des GSC München e.V. - Neuer Eintrag: ".$tanzpartnersuche->getUsername();
            $emailBody = "Ein neuer Eintrag wurde angelegt. \n";
            $emailBody .= "\n";
            $emailBody .= "Der Nutzer ".$tanzpartnersuche->getUsername()." hat seinen Eintrag in der Tanzpartnersuche angelegt und den Freischaltungslink erhalten.\n";
            $emailBody .= "\n";
            $emailBody .= "Nutzername: ".$tanzpartnersuche->getUsername()." \n";
            $emailBody .= "E-Mail:     ".$tanzpartnersuche->getEmail()." \n";
            $emailBody .= "Profil:     ".$tanzpartnersuche->getProfile()." \n";
            $emailBody .= "\n";
            $emailBody .= "Ende der Nachricht\n";

            $message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
            $message->setTo("tanzpartner@gsc-muenchen.de")
                    ->setFrom("tanzpartner@gsc-muenchen.de")
                    ->setSubject($mailSubject);

            $message->setBody($emailBody, 'text/plain');
            $message->send();

        }
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("user")
     * @return string|object|null|void
     */
    public function editAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function updateAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->update($user);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function deleteAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->remove($user);
        $this->redirect('list');
    }

    /**
     * action login
     *
     * @return string|object|null|void
     */
    public function loginAction()
    {
    }

    /**
     * @param \GSC\Tanzpartnersuche\Domain\Repository\UserRepository $userRepository
     */
    public function injectUserRepository(\GSC\Tanzpartnersuche\Domain\Repository\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}
