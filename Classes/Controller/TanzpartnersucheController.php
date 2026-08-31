<?php

namespace Gsc\Tanzpartnersuche\Controller;

use TYPO3\CMS\Core\Crypto\PasswordHashing\InvalidPasswordHashException;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Gsc\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository;
use Gsc\Tanzpartnersuche\Domain\Model\Tanzpartnersuche;
use Gsc\Tanzpartnersuche\Service\RateLimiterService;
use Psr\Http\Message\ResponseInterface;

class TanzpartnersucheController extends ActionController {

    private const LANGUAGE_FILE = 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:';
    private const SESSION_KEY = 'tx_tanzpartnersuche_userId';
    private const SESSION_ACTIVITY_KEY = 'tx_tanzpartnersuche_lastActivity';
    private const SESSION_TIMEOUT_SECONDS = 30 * 60;

    private const GENDER_VALUES = [1, 2];
    private const CATEGORY_VALUES = [1, 2, 3];
    private const LEVEL_VALUES = [1, 2, 3, 4, 5, 6, 7, 8];

    private const USERNAME_MIN_LENGTH = 3;
    private const USERNAME_MAX_LENGTH = 50;
    private const PASSWORD_MIN_LENGTH = 10;
    private const BIO_MAX_LENGTH = 2000;
    private const MESSAGE_MIN_LENGTH = 10;
    private const MESSAGE_MAX_LENGTH = 5000;
    private const SENDER_NAME_MIN_LENGTH = 5;
    private const SENDER_NAME_MAX_LENGTH = 100;
    private const SURVEY_COMMENT_MAX_LENGTH = 2000;
    private const SURVEY_REASON_VALUES = [1, 2, 3, 4, 5, 6];
    private const SURVEY_REASON_TRANSLATION_KEYS = [
        1 => 'deleteSurveyReason1',
        2 => 'deleteSurveyReason2',
        3 => 'deleteSurveyReason3',
        4 => 'deleteSurveyReason4',
        5 => 'deleteSurveyReason5',
        6 => 'deleteSurveyReason6',
    ];
    private const SURVEY_RECIPIENT = 'webservice@gsc-muenchen.de';
    private const IMPRESSUM_URL = 'https://www.gsc-muenchen.de/impressum';

    private const VERIFICATION_VALIDITY_SECONDS = 48 * 3600;
    private const RESET_VALIDITY_SECONDS = 2 * 3600;

    // Wird von getLoggedInUser() gesetzt, sobald eine vorhandene Session wegen Inaktivität
    // verworfen wurde, damit redirectDueToLoginRequired() die passende Meldung anzeigen kann.
    private bool $sessionTimedOut = false;

    // Constructor
    public function __construct(
        private readonly TanzpartnersucheRepository $tanzpartnersucheRepository,
        private readonly MailerInterface $mailer,
        private readonly PersistenceManagerInterface $persistenceManager,
        private readonly RateLimiterService $rateLimiterService,
    ) {
    }

    // Main
    public function mainAction(): ResponseInterface {
        $user = $this->getLoggedInUser();
        if ($user instanceof Tanzpartnersuche) {
            $this->view->assign('isLoggedIn', true);
            $this->view->assign('loggedInUsername', $user->getUsername());
        }

        return $this->htmlResponse();
    }

    // Search
    public function searchAction(): ResponseInterface {
        $myGender = null;
        $searchRole = null;
        $searchCategory = null;
        $levels = [];

        if ($this->request->hasArgument('myGender')) {
            $value = (int)$this->request->getArgument('myGender');
            if (in_array($value, self::GENDER_VALUES, true)) {
                $myGender = $value;
                $this->view->assign('myGender', $myGender);
            }
        }
        if ($this->request->hasArgument('searchRole')) {
            $value = (int)$this->request->getArgument('searchRole');
            if (in_array($value, self::GENDER_VALUES, true)) {
                $searchRole = $value;
                $this->view->assign('searchRole', $searchRole);
            }
        }
        if ($this->request->hasArgument('searchCategory')) {
            $value = (int)$this->request->getArgument('searchCategory');
            if (in_array($value, self::CATEGORY_VALUES, true)) {
                $searchCategory = $value;
                $this->view->assign('searchCategory', $searchCategory);
            }
        }
        foreach (self::LEVEL_VALUES as $levelNumber) {
            $argumentName = sprintf('level%02d', $levelNumber);
            if ($this->request->hasArgument($argumentName)) {
                $value = (int)$this->request->getArgument($argumentName);
                if ($value === $levelNumber) {
                    $levels[] = $levelNumber;
                    $this->view->assign($argumentName, $value);
                }
            }
        }

        if ($myGender !== null || $searchRole !== null || $searchCategory !== null || $levels !== []) {
            $users = $this->tanzpartnersucheRepository->findBySearchCriteria($myGender, $searchRole, $searchCategory, $levels);
            $this->view->assign('users', $users);
        }

        return $this->htmlResponse();
    }

    // Help
    public function helpAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // New
    // $prefill ist ausschließlich für den internen Forward aus redirectToNewWithError() gedacht.
    // Ein von außen übergebener uid-Wert würde Extbase dazu bringen, einen bereits persistierten
    // (fremden) Datensatz aus der DB zu laden - das würde dessen Profildaten (u.a. E-Mail) in das
    // eigentlich leere Registrierungsformular durchsickern lassen. Da ein frisch abgelehnter
    // Registrierungsversuch nie eine uid hat, wird ein Objekt mit gesetzter uid ignoriert.
    public function newAction(?Tanzpartnersuche $prefill = null): ResponseInterface {
        if ($prefill !== null && $prefill->getUid() !== null) {
            $prefill = null;
        }
        $this->view->assign('newTanzpartnersuche', $prefill);
        return $this->htmlResponse();
    }

    // Login
    public function loginAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // Detail
    public function detailAction(
        Tanzpartnersuche $user,
        ?int $myGender = null,
        ?int $searchRole = null,
        ?int $searchCategory = null,
        ?int $level01 = null,
        ?int $level02 = null,
        ?int $level03 = null,
        ?int $level04 = null,
        ?int $level05 = null,
        ?int $level06 = null,
        ?int $level07 = null,
        ?int $level08 = null,
        ?string $sender = null,
        ?string $sendermail = null,
        ?string $message = null,
        array $mailErrors = [],
    ): ResponseInterface {
        $this->view->assign('user', $user);
        $this->view->assign('recipientUid', $user->getUid());
        $this->view->assign('sender', $sender ?? '');
        $this->view->assign('sendermail', $sendermail ?? '');
        $this->view->assign('message', $message ?? '');
        $this->view->assign('mailErrors', $mailErrors);

        $searchFilter = $this->buildValidSearchFilter(
            $myGender, $searchRole, $searchCategory,
            $level01, $level02, $level03, $level04, $level05, $level06, $level07, $level08
        );
        foreach ($searchFilter as $argumentName => $value) {
            $this->view->assign($argumentName, $value);
        }

        return $this->htmlResponse();
    }

    // Create
    public function createAction(Tanzpartnersuche $newTanzpartnersuche): ResponseInterface {
        $username = trim($newTanzpartnersuche->getUsername());
        $email = trim($newTanzpartnersuche->getEmail());
        $password = $newTanzpartnersuche->getPassword();
        $passwordConfirmation = $newTanzpartnersuche->getPasswordconfirmation();

        // Zwei Limiter: einer pro Absender-IP (verhindert Massenanlage von Datensätzen),
        // einer pro Ziel-E-Mail (verhindert gezieltes Mail-Bombing eines einzelnen Postfachs).
        $ipAccepted = $this->rateLimiterService->forRegistration($this->getClientIp())->consume(1)->isAccepted();
        $recipientAccepted = $email === '' || $this->rateLimiterService->forRegistrationRecipient($email)->consume(1)->isAccepted();
        if (!$ipAccepted || !$recipientAccepted) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_rate_limited');
        }

        if (!$this->isValidUsername($username)) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_username');
        }

        if (!GeneralUtility::validEmail($email) || mb_strlen($email) > 255) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_email');
        }

        if ($password === '' || $password !== $passwordConfirmation || !$this->isValidPassword($password)) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_password');
        }

        if (!$this->isInRange($newTanzpartnersuche->getHeight(), 100, 250)) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_height');
        }

        if (!$this->isInRange($newTanzpartnersuche->getAge(), 16, 120)) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_age');
        }

        if (!in_array($newTanzpartnersuche->getGender(), self::GENDER_VALUES, true)
            || !in_array($newTanzpartnersuche->getRole(), self::GENDER_VALUES, true)
            || !in_array($newTanzpartnersuche->getCategory(), self::CATEGORY_VALUES, true)
            || !in_array($newTanzpartnersuche->getLevel(), self::LEVEL_VALUES, true)
        ) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_selection');
        }

        if (mb_strlen($newTanzpartnersuche->getBio()) > self::BIO_MAX_LENGTH) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_invalid_bio');
        }

        if ($this->tanzpartnersucheRepository->findOneByEmail($email) !== null) {
            return $this->redirectToNewWithError($newTanzpartnersuche, 'error_duplicate_email');
        }

        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');

        $newTanzpartnersuche->setUsername($username);
        $newTanzpartnersuche->setEmail($email);
        $newTanzpartnersuche->setPassword($hashInstance->getHashedPassword($password));
        $newTanzpartnersuche->setPasswordconfirmation('');
        $newTanzpartnersuche->setCreated(time());
        $newTanzpartnersuche->setChanged(time());
        $newTanzpartnersuche->setHidden(true);

        $verificationcode = bin2hex(random_bytes(16));
        $newTanzpartnersuche->setVerificationcode($verificationcode);

        $this->tanzpartnersucheRepository->add($newTanzpartnersuche);
        $this->persistenceManager->persistAll();

        $this->sendVerificationMail($email, $username, $verificationcode);
        $this->sendProfileNotificationMail($newTanzpartnersuche, true);

        $this->addFlashMessage($this->translate('success_registration'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('verify');
    }

    // Verify (shows the code-entry form)
    public function verifyAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // Verify (processes the submitted code)
    public function statusAction(Tanzpartnersuche $verifyTanzpartnersuche): ResponseInterface {
        if (!$this->rateLimiterService->forVerification($this->getClientIp())->consume(1)->isAccepted()) {
            $this->addFlashMessage($this->translate('error_rate_limited'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('verify');
        }

        $username = trim($verifyTanzpartnersuche->getUsername());
        $email = trim($verifyTanzpartnersuche->getEmail());
        $code = trim($verifyTanzpartnersuche->getVerificationcode());

        // Die E-Mail-Adresse ist eindeutig (der Username kann mehrfach vergeben sein),
        // daher wird darüber der Datensatz eindeutig ermittelt; Username und Code
        // müssen zusätzlich zu genau diesem Datensatz passen.
        $user = $email !== '' ? $this->tanzpartnersucheRepository->findOneByEmail($email) : null;

        $isValid = $user instanceof Tanzpartnersuche
            && $user->isHidden()
            && $username !== ''
            && hash_equals($user->getUsername(), $username)
            && $code !== ''
            && $user->getVerificationcode() !== ''
            && hash_equals($user->getVerificationcode(), $code)
            && (time() - $user->getCreated()) <= self::VERIFICATION_VALIDITY_SECONDS;

        if (!$isValid) {
            $this->addFlashMessage($this->translate('error_verification_failed'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('verify');
        }

        $user->setHidden(false);
        $user->setChanged(time());
        $this->tanzpartnersucheRepository->update($user);

        $this->addFlashMessage($this->translate('success_verification'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('verified');
    }

    // Verify (shows the success message after verification)
    public function verifiedAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // Login (processes submitted credentials, or re-shows the menu for an already logged-in user)
    public function loggedinAction(?Tanzpartnersuche $loginTanzpartnersuche = null): ResponseInterface {
        if ($loginTanzpartnersuche instanceof Tanzpartnersuche) {
            if (!$this->rateLimiterService->forLogin($this->getClientIp())->consume(1)->isAccepted()) {
                $this->addFlashMessage($this->translate('error_rate_limited'), '', ContextualFeedbackSeverity::ERROR);
                return $this->redirect('login');
            }

            $email = trim($loginTanzpartnersuche->getEmail());
            $password = $loginTanzpartnersuche->getPassword();

            // Die E-Mail-Adresse ist eindeutig (der Username kann mehrfach vergeben sein)
            // und daher der einzige verlässliche Login-Identifikator.
            $user = $email !== '' ? $this->tanzpartnersucheRepository->findOneByEmail($email) : null;

            $passwordValid = $user instanceof Tanzpartnersuche && $this->passwordMatches($user, $password);

            if (!$user instanceof Tanzpartnersuche || $user->isHidden() || !$passwordValid) {
                $this->addFlashMessage($this->translate('error_login_failed'), '', ContextualFeedbackSeverity::ERROR);
                return $this->redirect('login');
            }

            $frontendUser = $this->request->getAttribute('frontend.user');
            $frontendUser->setAndSaveSessionData(self::SESSION_KEY, $user->getUid());
            $frontendUser->setAndSaveSessionData(self::SESSION_ACTIVITY_KEY, time());

            // Ein Login verlängert die Sichtbarkeit im Frontend (siehe getVisibleUntil())
            // sowie die Frist bis zum endgültigen Löschen inaktiver Profile um weitere 6 Monate.
            $user->setChanged(time());
            $this->tanzpartnersucheRepository->update($user);

            $this->addFlashMessage($this->translate('success_login'), '', ContextualFeedbackSeverity::OK);
            $this->view->assign('loginTanzpartnersuche', $user);
            return $this->htmlResponse();
        }

        // Kein Formular übermittelt: Rücksprung auf die Menüseite nach Profil-/Passwortänderung.
        $user = $this->getLoggedInUser();
        if (!$user instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }

        $this->view->assign('loginTanzpartnersuche', $user);
        return $this->htmlResponse();
    }

    // Logout
    public function logoutAction(): ResponseInterface {
        $frontendUser = $this->request->getAttribute('frontend.user');
        $frontendUser?->setAndSaveSessionData(self::SESSION_KEY, null);
        $frontendUser?->setAndSaveSessionData(self::SESSION_ACTIVITY_KEY, null);

        $this->addFlashMessage($this->translate('success_logout'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('main');
    }

    // Edit (shows the edit form for the logged-in user's own entry)
    // $prefill ist ausschließlich für den internen Forward aus redirectToEditWithError() gedacht,
    // um nach einem fehlgeschlagenen Update die zuletzt eingegebenen (eigenen) Werte erneut
    // anzuzeigen. Ein von außen übergebener fremder uid-Wert würde sonst Extbase dazu bringen,
    // das Profil eines beliebigen anderen Nutzers zu laden und dessen Daten (u.a. E-Mail) in das
    // Formular durchsickern zu lassen - daher wird alles verworfen, was nicht zum eingeloggten
    // Nutzer gehört.
    public function editAction(?Tanzpartnersuche $prefill = null): ResponseInterface {
        $loggedInUser = $this->getLoggedInUser();
        if (!$loggedInUser instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }

        if ($prefill !== null && $prefill->getUid() !== $loggedInUser->getUid()) {
            $prefill = null;
        }

        $this->view->assign('editTanzpartnersuche', $prefill ?? $loggedInUser);
        return $this->htmlResponse();
    }

    // Edit (processes the submitted profile changes)
    public function updateAction(Tanzpartnersuche $editTanzpartnersuche): ResponseInterface {
        $loggedInUser = $this->getLoggedInUser();
        if (!$loggedInUser instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }
        if ($loggedInUser->getUid() !== $editTanzpartnersuche->getUid()) {
            return $this->redirect('login');
        }

        $username = trim($editTanzpartnersuche->getUsername());

        if (!$this->isValidUsername($username)) {
            return $this->redirectToEditWithError($editTanzpartnersuche, 'error_invalid_username');
        }

        if (!$this->isInRange($editTanzpartnersuche->getHeight(), 100, 250)) {
            return $this->redirectToEditWithError($editTanzpartnersuche, 'error_invalid_height');
        }

        if (!$this->isInRange($editTanzpartnersuche->getAge(), 16, 120)) {
            return $this->redirectToEditWithError($editTanzpartnersuche, 'error_invalid_age');
        }

        if (!in_array($editTanzpartnersuche->getGender(), self::GENDER_VALUES, true)
            || !in_array($editTanzpartnersuche->getRole(), self::GENDER_VALUES, true)
            || !in_array($editTanzpartnersuche->getCategory(), self::CATEGORY_VALUES, true)
            || !in_array($editTanzpartnersuche->getLevel(), self::LEVEL_VALUES, true)
        ) {
            return $this->redirectToEditWithError($editTanzpartnersuche, 'error_invalid_selection');
        }

        if (mb_strlen($editTanzpartnersuche->getBio()) > self::BIO_MAX_LENGTH) {
            return $this->redirectToEditWithError($editTanzpartnersuche, 'error_invalid_bio');
        }

        $editTanzpartnersuche->setUsername($username);
        $editTanzpartnersuche->setChanged(time());
        $this->tanzpartnersucheRepository->update($editTanzpartnersuche);

        $this->sendProfileNotificationMail($editTanzpartnersuche, false);

        $this->addFlashMessage($this->translate('success_profile_updated'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('loggedin');
    }

    // Change password (shows the form for the logged-in user)
    public function changepasswordAction(): ResponseInterface {
        if (!$this->getLoggedInUser() instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }

        return $this->htmlResponse();
    }

    // Change password (processes the submitted passwords)
    public function savepasswordAction(string $currentpassword, string $password, string $passwordconfirmation): ResponseInterface {
        $user = $this->getLoggedInUser();
        if (!$user instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }

        if (!$this->passwordMatches($user, $currentpassword)) {
            $this->addFlashMessage($this->translate('error_current_password_invalid'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('changepassword');
        }

        if ($password === '' || $password !== $passwordconfirmation || !$this->isValidPassword($password)) {
            $this->addFlashMessage($this->translate('error_invalid_password'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('changepassword');
        }

        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        $user->setPassword($hashInstance->getHashedPassword($password));
        $user->setChanged(time());
        $this->tanzpartnersucheRepository->update($user);

        $this->addFlashMessage($this->translate('success_password_changed'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('loggedin');
    }

    // Delete (shows the confirmation form for the logged-in user)
    public function deleteAction(): ResponseInterface {
        if (!$this->getLoggedInUser() instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }

        return $this->htmlResponse();
    }

    // Delete (processes the confirmed deletion)
    public function deleteconfirmAction(string $password): ResponseInterface {
        $user = $this->getLoggedInUser();
        if (!$user instanceof Tanzpartnersuche) {
            return $this->redirectDueToLoginRequired();
        }

        if (!$this->passwordMatches($user, $password)) {
            $this->addFlashMessage($this->translate('error_current_password_invalid'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('delete');
        }

        $this->tanzpartnersucheRepository->removePermanently($user);

        $frontendUser = $this->request->getAttribute('frontend.user');
        $frontendUser?->setAndSaveSessionData(self::SESSION_KEY, null);
        $frontendUser?->setAndSaveSessionData(self::SESSION_ACTIVITY_KEY, null);

        return $this->redirect('deletesurvey');
    }

    // Delete survey (shown once, directly after account deletion; the account is already gone by this point)
    public function deletesurveyAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // Delete survey (processes the optional, voluntary feedback submitted after account deletion)
    public function deletesurveysendAction(int $reason, string $comment = ''): ResponseInterface {
        if (!$this->rateLimiterService->forDeleteSurvey($this->getClientIp())->consume(1)->isAccepted()) {
            return $this->redirect('deletesurveysent');
        }

        if (!in_array($reason, self::SURVEY_REASON_VALUES, true)) {
            $reason = 1;
        }

        $comment = trim($comment);
        if (!$this->isValidSurveyComment($comment)) {
            $comment = '';
        }

        $reasonText = $this->translate(self::SURVEY_REASON_TRANSLATION_KEYS[$reason]);

        $emailBody = $this->translate('mail_delete_survey_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= $this->translateFormat('mail_delete_survey_reason_line', $reasonText)."\n";
        $emailBody .= $this->translateFormat('mail_delete_survey_feedback_line', $comment !== '' ? $comment : $this->translate('mail_no_info'))."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_closing_greeting')."\n";
        $emailBody .= $this->translate('mail_org_name')."\n";

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->setTo(self::SURVEY_RECIPIENT)
            ->setSubject($this->translate('mail_survey_subject'))
            ->text($emailBody);
        $this->mailer->send($mail);

        return $this->redirect('deletesurveysent');
    }

    // Delete survey: success page shown after the feedback was sent
    public function deletesurveysentAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // Contact request from a profile's detail page
    public function mailAction(
        string $sender,
        string $sendermail,
        string $message,
        int $recipientUid,
        ?int $myGender = null,
        ?int $searchRole = null,
        ?int $searchCategory = null,
        ?int $level01 = null,
        ?int $level02 = null,
        ?int $level03 = null,
        ?int $level04 = null,
        ?int $level05 = null,
        ?int $level06 = null,
        ?int $level07 = null,
        ?int $level08 = null,
    ): ResponseInterface {
        $sender = trim($sender);
        $sendermail = trim($sendermail);
        $message = trim($message);

        $searchFilter = $this->buildValidSearchFilter(
            $myGender, $searchRole, $searchCategory,
            $level01, $level02, $level03, $level04, $level05, $level06, $level07, $level08
        );

        $recipient = $this->tanzpartnersucheRepository->findByUid($recipientUid);
        if (!$recipient instanceof Tanzpartnersuche) {
            $this->addFlashMessage($this->translate('error_mail_recipient_not_found'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('main');
        }

        // Zwei Limiter: einer pro Absender-IP (verhindert automatisiertes Massen-Mailing),
        // einer pro angeschriebenem Profil (verhindert das gezielte Zuspammen eines einzelnen Postfachs).
        $ipAccepted = $this->rateLimiterService->forContactMail($this->getClientIp())->consume(1)->isAccepted();
        $recipientAccepted = $this->rateLimiterService->forContactMailRecipient((string)$recipientUid)->consume(1)->isAccepted();
        if (!$ipAccepted || !$recipientAccepted) {
            return $this->redirectToDetailWithErrors($recipient, $sender, $sendermail, $message, [], $searchFilter, 'error_rate_limited');
        }

        $mailErrors = [];
        if (!$this->isValidSenderName($sender)) {
            $mailErrors['sender'] = $this->translate('error_mail_invalid_sender');
        }
        if (!GeneralUtility::validEmail($sendermail) || mb_strlen($sendermail) > 255) {
            $mailErrors['sendermail'] = $this->translate('error_mail_invalid_email');
        }
        if (!$this->isValidMessage($message)) {
            $mailErrors['message'] = $this->translate('error_mail_invalid_message');
        }
        if ($mailErrors !== []) {
            return $this->redirectToDetailWithErrors($recipient, $sender, $sendermail, $message, $mailErrors, $searchFilter);
        }

        $emailBody = $this->translateFormat('mail_greeting_hello', $recipient->getUsername())."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_contact_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translateFormat('mail_contact_replied_line', $sender)."\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= $this->translateFormat('mail_contact_name_line', $sender)."\n";
        $emailBody .= $this->translateFormat('mail_contact_email_line', $sendermail)."\n";
        $emailBody .= $this->translateFormat('mail_contact_message_line', $message)."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_contact_notice');
        $emailBody .= $this->translateFormat('mail_contact_reply_instruction', $sender)."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_closing_thanks')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->mailFooter();

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->setTo($recipient->getEmail())
            ->setReplyTo($sendermail, $sender)
            ->setSubject($this->translate('mail_contact_subject'))
            ->text($emailBody);
        $this->mailer->send($mail);

        $this->addFlashMessage($this->translate('success_mail_sent'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('mailsent', null, null, array_merge($searchFilter, [
            'recipientName' => $recipient->getUsername(),
            'sender' => $sender,
            'sendermail' => $sendermail,
            'message' => $message,
        ]));
    }

    // Contact request: success page shown after the mail was sent
    public function mailsentAction(
        string $recipientName = '',
        string $sender = '',
        string $sendermail = '',
        string $message = '',
        ?int $myGender = null,
        ?int $searchRole = null,
        ?int $searchCategory = null,
        ?int $level01 = null,
        ?int $level02 = null,
        ?int $level03 = null,
        ?int $level04 = null,
        ?int $level05 = null,
        ?int $level06 = null,
        ?int $level07 = null,
        ?int $level08 = null,
    ): ResponseInterface {
        $searchFilter = $this->buildValidSearchFilter(
            $myGender, $searchRole, $searchCategory,
            $level01, $level02, $level03, $level04, $level05, $level06, $level07, $level08
        );
        foreach ($searchFilter as $argumentName => $value) {
            $this->view->assign($argumentName, $value);
        }

        $this->view->assignMultiple([
            'recipientName' => $recipientName,
            'sender' => $sender,
            'sendermail' => $sendermail,
            'message' => $message,
        ]);

        return $this->htmlResponse();
    }

    // Password reset: shows the "request a reset link" form
    public function resetpwAction(): ResponseInterface {
        return $this->htmlResponse();
    }

    // Password reset: processes the requested email address
    public function sendresetAction(string $email): ResponseInterface {
        if (!$this->rateLimiterService->forPasswordReset($this->getClientIp())->consume(1)->isAccepted()) {
            $this->addFlashMessage($this->translate('error_rate_limited'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('main');
        }

        $email = trim($email);

        if (GeneralUtility::validEmail($email)) {
            $user = $this->tanzpartnersucheRepository->findOneByEmail($email);
            if ($user instanceof Tanzpartnersuche) {
                $resetcode = bin2hex(random_bytes(32));
                $user->setResetcode($resetcode);
                $user->setResetcodecreated(time());
                $this->tanzpartnersucheRepository->update($user);
                $this->persistenceManager->persistAll();

                $this->sendResetMail($user->getEmail(), $user->getUsername(), $resetcode);
            }
        }

        // Immer dieselbe Meldung, unabhängig vom Ergebnis, um E-Mail-Enumeration zu verhindern.
        $this->addFlashMessage($this->translate('info_reset_requested'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('main');
    }

    // Password reset: shows the "set a new password" form
    public function newpasswordAction(string $email, string $token): ResponseInterface {
        $this->view->assign('email', $email);
        $this->view->assign('token', $token);
        return $this->htmlResponse();
    }

    // Password reset: processes the new password
    public function updatepasswordAction(string $email, string $token, string $password, string $passwordconfirmation): ResponseInterface {
        $email = trim($email);
        $user = $email !== '' ? $this->tanzpartnersucheRepository->findOneByEmail($email) : null;

        $tokenValid = $user instanceof Tanzpartnersuche
            && $token !== ''
            && $user->getResetcode() !== ''
            && hash_equals($user->getResetcode(), $token)
            && (time() - $user->getResetcodecreated()) <= self::RESET_VALIDITY_SECONDS;

        if (!$tokenValid) {
            $this->addFlashMessage($this->translate('error_reset_invalid'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('resetpw');
        }

        if ($password === '' || $password !== $passwordconfirmation || !$this->isValidPassword($password)) {
            $this->addFlashMessage($this->translate('error_invalid_password'), '', ContextualFeedbackSeverity::ERROR);
            return $this->redirect('newpassword', null, null, ['email' => $email, 'token' => $token]);
        }

        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        $user->setPassword($hashInstance->getHashedPassword($password));
        $user->setResetcode('');
        $user->setResetcodecreated(0);
        $user->setChanged(time());
        $this->tanzpartnersucheRepository->update($user);

        $this->addFlashMessage($this->translate('success_password_updated'), '', ContextualFeedbackSeverity::OK);
        return $this->redirect('login');
    }

    private function getLoggedInUser(): ?Tanzpartnersuche {
        $frontendUser = $this->request->getAttribute('frontend.user');
        $userId = $frontendUser?->getSessionData(self::SESSION_KEY);
        if (!is_int($userId)) {
            return null;
        }

        // Inaktivitäts-Timeout: läuft die Session ab, wird sie sofort beendet und der Aufrufer
        // (via redirectDueToLoginRequired()) zeigt eine entsprechende Meldung statt eines stillen Logins-Redirects.
        $lastActivity = $frontendUser->getSessionData(self::SESSION_ACTIVITY_KEY);
        if (!is_int($lastActivity) || (time() - $lastActivity) > self::SESSION_TIMEOUT_SECONDS) {
            $frontendUser->setAndSaveSessionData(self::SESSION_KEY, null);
            $frontendUser->setAndSaveSessionData(self::SESSION_ACTIVITY_KEY, null);
            $this->sessionTimedOut = true;
            return null;
        }

        $frontendUser->setAndSaveSessionData(self::SESSION_ACTIVITY_KEY, time());

        $user = $this->tanzpartnersucheRepository->findByUid($userId);
        return ($user instanceof Tanzpartnersuche && !$user->isHidden()) ? $user : null;
    }

    // Leitet auf 'login' um, wenn nie eingeloggt wurde, bzw. mit Flash-Message ins Hauptmenü,
    // wenn eine bestehende Session gerade wegen 30 Minuten Inaktivität beendet wurde.
    private function redirectDueToLoginRequired(): ResponseInterface {
        if ($this->sessionTimedOut) {
            $this->addFlashMessage($this->translate('info_session_timeout'), '', ContextualFeedbackSeverity::INFO);
            return $this->redirect('main');
        }

        return $this->redirect('login');
    }

    private function getClientIp(): string {
        $normalizedParams = $this->request->getAttribute('normalizedParams');
        return $normalizedParams instanceof NormalizedParams ? $normalizedParams->getRemoteAddress() : 'unknown';
    }

    private function passwordMatches(Tanzpartnersuche $user, string $password): bool {
        if ($password === '') {
            return false;
        }

        try {
            return GeneralUtility::makeInstance(PasswordHashFactory::class)
                ->get($user->getPassword(), 'FE')
                ->checkPassword($password, $user->getPassword());
        } catch (InvalidPasswordHashException) {
            return false;
        }
    }

    private function redirectToDetailWithErrors(
        Tanzpartnersuche $recipient,
        string $sender,
        string $sendermail,
        string $message,
        array $mailErrors,
        array $searchFilter,
        string $flashMessageTranslationKey = 'error_mail_form_invalid',
    ): ResponseInterface {
        $this->addFlashMessage($this->translate($flashMessageTranslationKey), '', ContextualFeedbackSeverity::ERROR);

        // ForwardResponse statt redirect(): siehe redirectToNewWithError() für die Begründung.
        return (new ForwardResponse('detail'))->withArguments(array_merge([
            'user' => $recipient,
            'sender' => $sender,
            'sendermail' => $sendermail,
            'message' => $message,
            'mailErrors' => $mailErrors,
        ], $searchFilter));
    }

    // Keeps only the search-filter arguments that pass the same whitelist checks as searchAction/detailAction,
    // so a filter can be threaded through the mail flow and back to the search page without adding new validation gaps.
    private function buildValidSearchFilter(
        ?int $myGender,
        ?int $searchRole,
        ?int $searchCategory,
        ?int $level01,
        ?int $level02,
        ?int $level03,
        ?int $level04,
        ?int $level05,
        ?int $level06,
        ?int $level07,
        ?int $level08,
    ): array {
        $filter = [];

        if ($myGender !== null && in_array($myGender, self::GENDER_VALUES, true)) {
            $filter['myGender'] = $myGender;
        }
        if ($searchRole !== null && in_array($searchRole, self::GENDER_VALUES, true)) {
            $filter['searchRole'] = $searchRole;
        }
        if ($searchCategory !== null && in_array($searchCategory, self::CATEGORY_VALUES, true)) {
            $filter['searchCategory'] = $searchCategory;
        }

        $levels = compact('level01', 'level02', 'level03', 'level04', 'level05', 'level06', 'level07', 'level08');
        foreach (self::LEVEL_VALUES as $levelNumber) {
            $argumentName = sprintf('level%02d', $levelNumber);
            if ($levels[$argumentName] === $levelNumber) {
                $filter[$argumentName] = $levelNumber;
            }
        }

        return $filter;
    }

    private function redirectToEditWithError(Tanzpartnersuche $editTanzpartnersuche, string $translationKey): ResponseInterface {
        $this->addFlashMessage($this->translate($translationKey), '', ContextualFeedbackSeverity::ERROR);

        // ForwardResponse statt redirect(): siehe redirectToNewWithError() für die Begründung.
        // 'prefill' statt 'user': editAction() akzeptiert darüber nur Daten des eingeloggten
        // Nutzers selbst (siehe dortiger Ownership-Check).
        return (new ForwardResponse('edit'))->withArguments(['prefill' => $editTanzpartnersuche]);
    }

    private function redirectToNewWithError(Tanzpartnersuche $newTanzpartnersuche, string $translationKey): ResponseInterface {
        // Passwort niemals im Klartext über einen Redirect (GET-Query) zurückschicken.
        $newTanzpartnersuche->setPassword('');
        $newTanzpartnersuche->setPasswordconfirmation('');

        $this->addFlashMessage($this->translate($translationKey), '', ContextualFeedbackSeverity::ERROR);

        // ForwardResponse statt redirect(): $newTanzpartnersuche ist ein frisches, noch nicht
        // persistiertes Objekt ohne uid. Der UriBuilder (für redirect()) kann ein solches Entity
        // nicht in eine URL serialisieren ("neither an Entity with identity properties set, nor
        // a Value Object"). ForwardResponse dispatcht intern weiter, ohne eine URL zu bauen.
        // 'prefill' statt 'user': newAction() akzeptiert darüber nur unpersistierte Objekte
        // (siehe dortiger uid-Check), damit sich darüber keine fremden Profile laden lassen.
        return (new ForwardResponse('new'))->withArguments(['prefill' => $newTanzpartnersuche]);
    }

    private function isValidUsername(string $username): bool {
        $length = mb_strlen($username);
        return $length >= self::USERNAME_MIN_LENGTH
            && $length <= self::USERNAME_MAX_LENGTH
            && preg_match('/^[\p{L}\p{N} _.\-]+$/u', $username) === 1;
    }

    private function isValidPassword(string $password): bool {
        return mb_strlen($password) >= self::PASSWORD_MIN_LENGTH;
    }

    // Whitelists plain name characters, rejecting HTML/script tags and control characters (e.g. mail header injection via CR/LF)
    private function isValidSenderName(string $sender): bool {
        $length = mb_strlen($sender);
        return $length >= self::SENDER_NAME_MIN_LENGTH
            && $length <= self::SENDER_NAME_MAX_LENGTH
            && preg_match('/^[\p{L}\p{N} .,\'\-]+$/u', $sender) === 1;
    }

    // Allows normal free text (incl. line breaks) but rejects any HTML/script tags
    private function isValidMessage(string $message): bool {
        $length = mb_strlen($message);
        return $length >= self::MESSAGE_MIN_LENGTH
            && $length <= self::MESSAGE_MAX_LENGTH
            && strip_tags($message) === $message;
    }

    // Optional free text (comment may be empty, incl. line breaks) but rejects any HTML/script tags
    private function isValidSurveyComment(string $comment): bool {
        return mb_strlen($comment) <= self::SURVEY_COMMENT_MAX_LENGTH
            && strip_tags($comment) === $comment;
    }

    private function isInRange(int $value, int $min, int $max): bool {
        return $value >= $min && $value <= $max;
    }

    private function translate(string $key): string {
        return LocalizationUtility::translate(self::LANGUAGE_FILE . $key) ?? $key;
    }

    private function translateFormat(string $key, string|int ...$args): string {
        return vsprintf($this->translate($key), $args);
    }

    // Gemeinsamer Footer (Adresse, Registergericht, Impressum-Link) für alle ausgehenden Mails.
    private function mailFooter(): string {
        $footer = $this->translate('mail_org_name')."\n";
        $footer .= $this->translate('mail_footer_street')."\n";
        $footer .= $this->translate('mail_footer_city')."\n";
        $footer .= "\n";
        $footer .= "---\n";
        $footer .= $this->translate('mail_footer_registergericht')."\n";
        $footer .= $this->translate('mail_footer_registernummer')."\n";
        $footer .= self::IMPRESSUM_URL;
        return $footer;
    }

    private function sendVerificationMail(string $email, string $username, string $verificationcode): void {
        $verifyUri = $this->uriBuilder->reset()->setCreateAbsoluteUri(true)->uriFor('verify');

        $emailBody = $this->translate('mail_verification_greeting')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_verification_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= $verificationcode."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_verification_activation_info')."\n";
        $emailBody .= $this->translate('mail_verification_link_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= $verifyUri."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= $this->translateFormat('mail_link_validity_line', (int)(self::VERIFICATION_VALIDITY_SECONDS / 3600))."\n";
        $emailBody .= $this->translate('mail_ignore_if_not_requested')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_closing_thanks')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->mailFooter();

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->setTo($email)
            ->setSubject($this->translate('mail_verification_subject'))
            ->text($emailBody);
        $this->mailer->send($mail);
    }

    // Benachrichtigt SURVEY_RECIPIENT über Neuanlage/Änderung eines Profils, mit einer
    // Zusammenfassung der Profildaten (ohne Passwort).
    private function sendProfileNotificationMail(Tanzpartnersuche $user, bool $isNew): void {
        $genderLabels = [1 => 'gender-female', 2 => 'gender-male'];
        $roleLabels = [1 => 'role-00', 2 => 'role-01'];
        $categoryLabels = [1 => 'category-00', 2 => 'category-01', 3 => 'category-02'];
        $levelLabels = [
            1 => 'level-00', 2 => 'level-01', 3 => 'level-02', 4 => 'level-03',
            5 => 'level-04', 6 => 'level-05', 7 => 'level-06', 8 => 'level-07',
        ];

        $gender = $this->translateOptional($genderLabels[$user->getGender()] ?? null);
        $role = $this->translateOptional($roleLabels[$user->getRole()] ?? null);
        $category = $this->translateOptional($categoryLabels[$user->getCategory()] ?? null);
        $level = $this->translateOptional($levelLabels[$user->getLevel()] ?? null);

        $emailBody = $this->translate($isNew ? 'mail_new_profile_intro' : 'mail_updated_profile_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= $this->translateFormat('mail_profile_username_line', $user->getUsername())."\n";
        $emailBody .= $this->translateFormat('mail_profile_height_line', $user->getHeight())."\n";
        $emailBody .= $this->translateFormat('mail_profile_age_line', $user->getAge())."\n";
        $emailBody .= $this->translateFormat('mail_profile_gender_line', $gender)."\n";
        $emailBody .= $this->translateFormat('mail_profile_role_line', $role)."\n";
        $emailBody .= $this->translateFormat('mail_profile_category_line', $category)."\n";
        $emailBody .= $this->translateFormat('mail_profile_level_line', $level)."\n";
        $emailBody .= $this->translateFormat('mail_profile_bio_line', $user->getBio() !== '' ? $user->getBio() : $this->translate('mail_no_info'))."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_closing_greeting')."\n";
        $emailBody .= $this->translate('mail_org_name')."\n";

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->setTo(self::SURVEY_RECIPIENT)
            ->setSubject($this->translate($isNew ? 'mail_new_profile_subject' : 'mail_updated_profile_subject'))
            ->text($emailBody);
        $this->mailer->send($mail);
    }

    private function translateOptional(?string $key): string {
        return $key !== null ? $this->translate($key) : '-';
    }

    private function sendResetMail(string $email, string $username, string $resetcode): void {
        $resetUri = $this->uriBuilder->reset()->setCreateAbsoluteUri(true)->uriFor(
            'newpassword',
            ['email' => $email, 'token' => $resetcode]
        );

        $emailBody = $this->translateFormat('mail_greeting_hello', $username)."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_reset_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_reset_link_intro')."\n";
        $emailBody .= "\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= $resetUri."\n";
        $emailBody .= "--------------------------------------------------------------------------------------------------------------\n";
        $emailBody .= "\n";
        $emailBody .= $this->translateFormat('mail_link_validity_line', (int)(self::RESET_VALIDITY_SECONDS / 3600))."\n";
        $emailBody .= $this->translate('mail_ignore_if_not_requested')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->translate('mail_closing_thanks')."\n";
        $emailBody .= "\n";
        $emailBody .= $this->mailFooter();

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->setTo($email)
            ->setSubject($this->translate('mail_reset_subject'))
            ->text($emailBody);
        $this->mailer->send($mail);
    }
}
