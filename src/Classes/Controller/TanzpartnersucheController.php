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
 * (c) 2022 Martin Arend <tanzpartner@gsc-muenchen.de>, GSC München e.V.
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
        // all checks passed - prepare next steps
        // generate verification code
        $verCode = date('y').rand(10,99).date('m').rand(10,99).date('d').rand(10,99).date('h').rand(10,99).date('i').rand(10,99).date('s').rand(10,99);
        $newTanzpartnersuche->setVerificationcode($verCode);

        // Password: salting and hashing
        $newTanzpartnersuche->setPassword(password_hash(($newTanzpartnersuche->getPassword()),PASSWORD_DEFAULT, array('cost' => 9)));
        $newTanzpartnersuche->setPasswordconfirmation('verified');

        // Hide User
        $newTanzpartnersuche->setHidden('1');

        // set created date
        $newTanzpartnersuche->setCreated(time());

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
        

        // send out mail to admin
        // assemble message
        $emailBody = "Es wurde erfolgreich ein neuer Eintrag in der Tanzpartnersuche angelegt. \n";
        $emailBody .= "\n";
        $emailBody .= "------------------------------------------------------------------------\n";
        $emailBody .= "Name: ".$newTanzpartnersuche->getUsername()."\n";
        $emailBody .= "Bio:  ".$newTanzpartnersuche->getBio()."\n";
        $emailBody .= "------------------------------------------------------------------------\n";
        $emailBody .= "Der Verifikationscode wurde verschickt. \n";
        $emailBody .= "-- Ende der Nachricht -- \n";

        // send mail
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
        $mail->to(new Address('tanzpartner@gsc-muenchen.de', 'tanzpartner@gsc-muenchen.de'));
        $mail->subject('Neuer Eintrag in der Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
        $mail->text($emailBody);
        $mail->send();

        // Add new profile to database
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
        // read full array from database
        $verifyTanzpartnersuche = $this->tanzpartnersucheRepository->findTanzpartnerByValidation($verifyTanzpartnersuche->getVerificationcode(),$verifyTanzpartnersuche->getUsername());
        
        // unhide dataset
        $verifyTanzpartnersuche->setHidden('0');

        // update database
        $this->tanzpartnersucheRepository->update($verifyTanzpartnersuche);

        // all done, display message to user
        $this->addFlashMessage('Der Eintrag wurde erfolgreich freigeschaltet. Viel Erfolg bei Deiner Tanzpartnersuche!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);

    }

    /**
     * action edit
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return string|object|null|void
     */
    public function editAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }
        
        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
    }

    /**
     * action update
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @Extbase\Validate(param="loginTanzpartnersuche" , validator="GSC\Tanzpartnersuche\Domain\Validator\EditTanzpartnersucheValidator")
     * @return string|object|null|void
     */
    public function updateAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }
        
        // send out mail to admin
        // assemble message
        $emailBody = "Ein Eintrag in der Tanzpartnersuche wurde geändert. \n";
        $emailBody .= "\n";
        $emailBody .= "------------------------------------------------------------------------\n";
        $emailBody .= "Name: ".$loginTanzpartnersuche->getUsername()."\n";
        $emailBody .= "Bio:  ".$loginTanzpartnersuche->getBio()."\n";
        $emailBody .= "------------------------------------------------------------------------\n";
        $emailBody .= "-- Ende der Nachricht -- \n";

        // send mail
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
        $mail->to(new Address('tanzpartner@gsc-muenchen.de', 'tanzpartner@gsc-muenchen.de'));
        $mail->subject('Geänderter Eintrag in der Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
        $mail->text($emailBody);
        $mail->send();

        // update profile in database
        $this->tanzpartnersucheRepository->update($loginTanzpartnersuche);
        
        // forward to loginMenu
        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
        $this->forward('loginMenu', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
    }

    /**
     * action delete
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return string|object|null|void
     */
    public function deleteAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }
        
        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
    }

    /**
     * action deleted
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return string|object|null|void
     */
    public function deletedAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {        
        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }
        
        // delete entry
        $this->tanzpartnersucheRepository->remove($loginTanzpartnersuche);

        // reset 
        $loginTanzpartnersuche = NULL;
    }

    /**
     * action feedback
     *
     * @return string|object|null|void
     */
    public function feedbackAction()
    {        
        // if there is feedback, send mail to admin
        if (($this->request->hasArgument('reason')) || ($this->request->hasArgument('comment'))) {
            if ($this->request->hasArgument('reason')) {
                $reason = $this->request->getArgument('reason');
            }
            if ($this->request->hasArgument('comment')) {
                $comment = $this->request->getArgument('comment');
            }
            // decode feedback
            switch ($reason) {
                case 0:
                    $reason = "Keine Auswahl";
                    break;
                case 1:
                    $reason = "Habe erfolgreich eine(n) Tanzpartner(in) über die Tanzpartnersuche gefunden";
                    break;
                case 2:
                    $reason = "Habe erfolgreich eine(n) Tanzpartner(in) durch die Vermittlung eines Trainers gefunden";
                    break;
                case 3:
                    $reason = "Habe erfolgreich eine(n) Tanzpartner(in) durch die Vermittlung eines Mitglieds gefunden";
                    break;
                case 4:
                    $reason = "Habe erfolgreich eine(n) Tanzpartner(in) außerhalb des Clubs gefunden";
                    break;
                case 5:
                    $reason = "Habe leider keine Tanzpartner(in) gefunden";
                    break;
                case 6:
                    $reason = "Komme mit dem System nicht zurecht/finde es unübersichtlich";
                    break;
            }
            
            // assemble message
            $emailBody = "Feedback zur Löschung eines Profils \n";
            $emailBody .= "\n";
            $emailBody .= "------------------------------------------------------------\n";
            $emailBody .= "Grund:     ".$reason."\n";
            $emailBody .= "Kommentar: ".$comment."\n";
            $emailBody .= "------------------------------------------------------------\n";
            $emailBody .= "Ende der Nachricht \n";

            // send mail
            $mail = GeneralUtility::makeInstance(MailMessage::class);
            $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
            $mail->to(new Address('tanzpartner@gsc-muenchen.de', 'tanzpartner@gsc-muenchen.de'));
            $mail->subject('Feedback zur Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
            $mail->text($emailBody);
            $mail->send();

        }

    }

    /**
     * action changepw
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return string|object|null|void
     */
    public function changepwAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }
        
        $loginTanzpartnersuche->setPassword('');
        $loginTanzpartnersuche->setPasswordconfirmation('');
        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
    }

    /**
     * action updatepw
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @Extbase\Validate(param="loginTanzpartnersuche" , validator="GSC\Tanzpartnersuche\Domain\Validator\PasswordTanzpartnersucheValidator")
     * @return string|object|null|void
     */
    public function updatepwAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }
        
        // hash password and update profile in database
        $loginTanzpartnersuche->setPassword(password_hash(($loginTanzpartnersuche->getPassword()),PASSWORD_DEFAULT, array('cost' => 9)));
        $loginTanzpartnersuche->setPasswordconfirmation('changed');
        $this->tanzpartnersucheRepository->update($loginTanzpartnersuche);
        
        // show results
        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
    }

    /**
     * action resetpw
     *
     * @return string|object|null|void
     */
    public function resetpwAction()
    {
    }

    /**
     * action resendpw
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $resetTanzpartnersuche
     * @return string|object|null|void
     */
    public function resendpwAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $resetTanzpartnersuche = NULL)
    {
        // check match with database, if positive send mail
        if (($this->request->hasArgument('username')) && ($this->request->hasArgument('email'))) {
            // read full array from database
            $resetTanzpartnersuche = $this->tanzpartnersucheRepository->UserPasswordReset($this->request->getArgument('username'), $this->request->getArgument('email'));

            // if match is positive
            if ($resetTanzpartnersuche != NULL) {
                // create initial password
                $pool = "0123456789+-!()#*=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $mix = str_shuffle($pool);
                $initialpw = substr($mix,0,12);
                
                // update array
                $resetTanzpartnersuche->setPassword(password_hash(($initialpw),PASSWORD_DEFAULT, array('cost' => 9)));
                $resetTanzpartnersuche->setPasswordconfirmation('initial');
                
                // update database
                $this->tanzpartnersucheRepository->update($resetTanzpartnersuche);

                // send mail to user with instructions
                // assemble message
                $emailBody = "Hallo vom Gelb-Schwarz Casino, \n";
                $emailBody .= "\n";
                $emailBody .= "Du hast Dein Passwort in der Tanzpartnersuche zurückgesetzt. \n";
                $emailBody .= "\n";
                $emailBody .= "Bitte logge Dich mit dem folgenden Passwort erneut ein:\n";
                $emailBody .= "\n";
                $emailBody .= "-----------------------------------------------------------------\n";
                $emailBody .= $initialpw."\n";
                $emailBody .= "-----------------------------------------------------------------\n";
                $emailBody .= "\n";
                $emailBody .= "Ändere nach der erfolgreichen Anmeldung dieses bitte umgehend!";
                $emailBody .= "\n";
                $emailBody .= "Besten Dank für die Nutzung der Tanzpartnersuche und viel Erfolg!\n";
                $emailBody .= "\n";
                $emailBody .= "Gelb-Schwarz-Casino München e.V.\n";
                $emailBody .= "Sonnenstraße 12a / II\n";
                $emailBody .= "D-80331 München\n";
                $emailBody .= "\n";
                $emailBody .= "---\n";
                $emailBody .= "Vertreten durch den Präsidenten Stefan Göttlinger \n";
                $emailBody .= "Registergericht: München \n";
                $emailBody .= "Registernummer: VR 4385\n";
                $emailBody .= "https://www.gsc-muenchen.de/impressum";

                // send mail
                $mail = GeneralUtility::makeInstance(MailMessage::class);
                $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
                $mail->to(new Address($resetTanzpartnersuche->getEmail(), $resetTanzpartnersuche->getEmail()));
                $mail->subject('Passwortreset für die Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
                $mail->text($emailBody);
                $mail->send();
            }

            // clear array
            $resetTanzpartnersuche = '';
        }
    }

    /**
     * action search
     *
     * @return string|object|null|void
     */
    public function searchAction()
    {
        // if radio buttons have been set, keep state if search form was submitted (again) und filter accordingly
        if (($this->request->hasArgument('myGender')) || ($this->request->hasArgument('searchRole')) || ($this->request->hasArgument('searchCategory')) || ($this->request->hasArgument('level01')) || ($this->request->hasArgument('level02')) || ($this->request->hasArgument('level03')) || ($this->request->hasArgument('level04')) || ($this->request->hasArgument('level05')) || ($this->request->hasArgument('level06')) || ($this->request->hasArgument('level07')) || ($this->request->hasArgument('level08'))) {
            if ($this->request->hasArgument('myGender')) {
                $mygender = $this->request->getArgument('myGender');
                $this->view->assign('myGender', $mygender);
            }
            if ($this->request->hasArgument('searchRole')) {
                $searchrole = $this->request->getArgument('searchRole');
                $this->view->assign('searchRole', $searchrole);
            }
            if ($this->request->hasArgument('searchCategory')) {
                $category = $this->request->getArgument('searchCategory');
                $this->view->assign('searchCategory', $category);
            }
            if ($this->request->hasArgument('level01')) {
                $level[1] = $this->request->getArgument('level01');
                $this->view->assign('level01', $level[1]);
            }
            if ($this->request->hasArgument('level02')) {
                $level[2] = $this->request->getArgument('level02');
                $this->view->assign('level02', $level[2]);
            }
            if ($this->request->hasArgument('level03')) {
                $level[3] = $this->request->getArgument('level03');
                $this->view->assign('level03', $level[3]);
            }
            if ($this->request->hasArgument('level04')) {
                $level[4] = $this->request->getArgument('level04');
                $this->view->assign('level04', $level[4]);
            }
            if ($this->request->hasArgument('level05')) {
                $level[5] = $this->request->getArgument('level05');
                $this->view->assign('level05', $level[5]);
            }
            if ($this->request->hasArgument('level06')) {
                $level[6] = $this->request->getArgument('level06');
                $this->view->assign('level06', $level[6]);
            }
            if ($this->request->hasArgument('level07')) {
                $level[7] = $this->request->getArgument('level07');
                $this->view->assign('level07', $level[7]);
            }
            if ($this->request->hasArgument('level08')) {
                $level[8] = $this->request->getArgument('level08');
                $this->view->assign('level08', $level[8]);
            }
            $tanzpartnersuches = $this->tanzpartnersucheRepository->filterProfiles($mygender, $searchrole, $category, $level);
            $this->view->assign('tanzpartnersuches', $tanzpartnersuches);
        }
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
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function detailAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->view->assign('tanzpartnersuche', $tanzpartnersuche);
    }

    /**
     * action login
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return string|object|null|void
     */
    public function loginAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche = NULL)
    {
    }

    /**
     * action loggedin
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @Extbase\Validate(param="loginTanzpartnersuche" , validator="GSC\Tanzpartnersuche\Domain\Validator\LoginTanzpartnersucheValidator")
     * @return void
     * 
     */
    public function loggedinAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // read full array from database
        $loginTanzpartnersuche = $this->tanzpartnersucheRepository->findTanzpartnerByUsername($loginTanzpartnersuche->getUsername());

        // add login timestamp to database to time-limit changes, check if empty before to avoid renewal
        if ($loginTanzpartnersuche->getLoggedin() == '') {
            $loginTanzpartnersuche->setLoggedin(strval(time()));
            $this->tanzpartnersucheRepository->update($loginTanzpartnersuche);
        }

        // forward to loginMenu
        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
        $this->forward('loginMenu', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        
    }

    /**
     * action loginMenu
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return void
     * 
     */
    public function loginMenuAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // read full array from database
        $loginTanzpartnersuche = $this->tanzpartnersucheRepository->findTanzpartnerByUsername($loginTanzpartnersuche->getUsername());

        // validate whether session is authorized
        if ($loginTanzpartnersuche->getLoggedin() == '') {    
            $this->addFlashMessage('Fehler: die Sitzung ist nicht autorisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // validate whether session is not expired
        if (($loginTanzpartnersuche->getLoggedin() + 1800) < time()) {
            $this->addFlashMessage('Deine Sitzung ist abgelaufen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->forward('logout', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        // if password has state "initial" because it was resetted, forward to changepw
        if ($loginTanzpartnersuche->getPasswordconfirmation() == 'initial') {
            $this->forward('changepw', NULL, NULL, ['loginTanzpartnersuche' => $loginTanzpartnersuche]);
        }

        $this->view->assign('loginTanzpartnersuche', $loginTanzpartnersuche);
    }

    /**
     * action logout
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche
     * @return string|object|null|void
     */
    public function logoutAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $loginTanzpartnersuche)
    {
        // remove timestamp from database as logout was successful
        $loginTanzpartnersuche->setLoggedin('');
        $this->tanzpartnersucheRepository->update($loginTanzpartnersuche);

        // reset 
        $loginTanzpartnersuche = NULL;
        
        // all done, display message to user
        $this->addFlashMessage('Du bist erfolgreich ausgeloggt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        
    }

    /**
     * action mail
     *
     * @param string $sender
     * @param string $sendermail
     * @param string $message
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function mailAction($sender, $sendermail, $message, \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        // send out contact mail to selected profile
        // assemble message
        $emailBody = "Hallo ".$tanzpartnersuche->getUsername().", \n";
        $emailBody .= "\n";
        $emailBody .= "Du hast eine Kontaktanfrage über die Tanzpartnersuche des Gelb-Schwarz-Casino München erhalten. \n";
        $emailBody .= "\n";
        $emailBody .= $sender." hat auf Dein Profil in der Tanzpartnersuche des GSC München e.V. geantwortet.\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "Name: ".$sender."\n";
        $emailBody .= "E-Mail: ".$sendermail."\n";
        $emailBody .= "Nachricht: ".$message."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= "Bitte beachte, dass jede weitere Kontaktaufnahme Deinerseits nun nicht mehr anonymisiert über unsere Plattform, sondern direkt über Deinen E-Mail Account erfolgt. ";
        $emailBody .= "Daher antworte bitte ".$sender." auch direkt per Mail. Du kannst dies direkt über die Antworten-Funktion Deines Mailclients tun.\n";
        $emailBody .= "\n";
        $emailBody .= "Besten Dank für die Nutzung der Tanzpartnersuche und viel Erfolg!\n";
        $emailBody .= "\n";
        $emailBody .= "Gelb-Schwarz-Casino München e.V.\n";
        $emailBody .= "Sonnenstraße 12a / II\n";
        $emailBody .= "D-80331 München\n";
        $emailBody .= "\n";
        $emailBody .= "---\n";
        $emailBody .= "Vertreten durch den Präsidenten Stefan Göttlinger \n";
        $emailBody .= "Registergericht: München \n";
        $emailBody .= "Registernummer: VR 4385\n";
        $emailBody .= "https://www.gsc-muenchen.de/impressum";

        // send mail
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
        $mail->to(new Address($tanzpartnersuche->getEmail(), $tanzpartnersuche->getEmail()));
        $mail->replyTo($sendermail);
        $mail->subject('Kontaktanfrage über die Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
        $mail->text($emailBody);
        $mail->send();

        // send copy to sendermail
        // assemble message
        $emailBody = "Hallo ".$sender.", \n";
        $emailBody .= "\n";
        $emailBody .= "Du hast folgende Kontaktanfrage über die Tanzpartnersuche des Gelb-Schwarz-Casino München verschickt: \n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "Empfänger: ".$tanzpartnersuche->getUsername()."\n";
        $emailBody .= "Deine Nachricht: ".$message."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= "Bitte beachte, dass jede weitere E-Mail-Kommunikation nun nicht mehr anonymisiert über unsere Plattform, sondern direkt über Eure E-Mail Accounts erfolgt.\n";
        $emailBody .= "\n";
        $emailBody .= "Besten Dank für die Nutzung der Tanzpartnersuche und viel Erfolg!\n";
        $emailBody .= "\n";
        $emailBody .= "Gelb-Schwarz-Casino München e.V.\n";
        $emailBody .= "Sonnenstraße 12a / II\n";
        $emailBody .= "D-80331 München\n";
        $emailBody .= "\n";
        $emailBody .= "---\n";
        $emailBody .= "Vertreten durch den Präsidenten Stefan Göttlinger \n";
        $emailBody .= "Registergericht: München \n";
        $emailBody .= "Registernummer: VR 4385\n";
        $emailBody .= "https://www.gsc-muenchen.de/impressum";

        // send mail
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->from(new \Symfony\Component\Mime\Address('tanzpartner@gsc-muenchen.de', 'Tanzpartnersuche des Gelb-Schwarz Casino München e.V.'));
        $mail->to(new Address($tanzpartnersuche->getEmail(), $tanzpartnersuche->getEmail()));
        $mail->replyTo($sendermail);
        $mail->subject('Kopie Deiner Kontaktanfrage über die Tanzpartnersuche des Gelb-Schwarz Casino München e.V.');
        $mail->text($emailBody);
        $mail->send();

        // show confirmation on frontend
        $this->addFlashMessage('Deine Anfrage an "'.$tanzpartnersuche->getUsername().'" wurde erfolgeich verschickt. Wir wünschen viel Erfolg bei der weiteren Kontaktaufnahme.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
    }
}
