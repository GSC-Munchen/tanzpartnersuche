<?php

namespace Gsc\Tanzpartnersuche\Controller\Backend;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Gsc\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository;

/**
 * Backend-Modul "Web > Tanzpartnersuche": Übersicht aller registrierten
 * Einträge, inkl. noch nicht verifizierter (versteckter) Profile, mit der
 * Möglichkeit, Einträge unwiderruflich zu löschen.
 */
class UserAdministrationController extends ActionController {

    private const LANGUAGE_FILE_MOD = 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_mod.xlf:';
    private const LANGUAGE_FILE = 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:';
    private const TABLE_NAME = 'tx_tanzpartnersuche_domain_model_tanzpartnersuche';

    private const GENDER_VALUES = [1, 2];
    private const CATEGORY_VALUES = [1, 2, 3];
    private const LEVEL_VALUES = [1, 2, 3, 4, 5, 6, 7, 8];

    private const USERNAME_MIN_LENGTH = 3;
    private const USERNAME_MAX_LENGTH = 50;
    private const BIO_MAX_LENGTH = 2000;

    // Muss mit RESET_VALIDITY_SECONDS in TanzpartnersucheController übereinstimmen,
    // da beide denselben Reset-Mechanismus (resetcode/resetcodecreated) bedienen.
    private const RESET_VALIDITY_SECONDS = 2 * 3600;

    // CType des Frontend-Plugins, über das die Passwort-Reset-Seite ermittelt wird,
    // da im Backend-Kontext keine aktuell gerenderte Seite existiert.
    private const PLUGIN_CTYPE = 'tanzpartnersuche_tanzpartnersuche';

    protected ModuleTemplate $moduleTemplate;

    public function __construct(
        private readonly TanzpartnersucheRepository $tanzpartnersucheRepository,
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly ConnectionPool $connectionPool,
        private readonly MailerInterface $mailer,
        private readonly SiteFinder $siteFinder,
    ) {
    }

    public function initializeAction(): void {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->moduleTemplate->setTitle(
            LocalizationUtility::translate(self::LANGUAGE_FILE_MOD . 'mlang_tabs_tab') ?? 'Tanzpartnersuche'
        );
        $this->moduleTemplate->setFlashMessageQueue($this->getFlashMessageQueue());
    }

    public function indexAction(): ResponseInterface {
        $users = $this->tanzpartnersucheRepository->findAllIncludingHidden();
        $this->moduleTemplate->assign('users', $users);
        $this->moduleTemplate->assign('isSystemMaintainer', $this->isSystemMaintainer());

        return $this->moduleTemplate->renderResponse('UserAdministration/Index');
    }

    /**
     * Zeigt die Detailansicht eines einzelnen Eintrags inkl. noch versteckter
     * (unverifizierter) Datensätze.
     */
    public function detailAction(int $uid): ResponseInterface {
        $user = $this->tanzpartnersucheRepository->findOneByUidIncludingHidden($uid);

        if ($user === null) {
            $this->addFlashMessage(
                LocalizationUtility::translate(self::LANGUAGE_FILE . 'backend_detail_not_found') ?? 'Der Eintrag wurde nicht gefunden.',
                '',
                ContextualFeedbackSeverity::ERROR
            );

            return $this->redirect('index');
        }

        $this->moduleTemplate->assign('user', $user);
        $this->moduleTemplate->assign('isSystemMaintainer', $this->isSystemMaintainer());

        return $this->moduleTemplate->renderResponse('UserAdministration/Detail');
    }

    /**
     * Zeigt die Bearbeitungsmaske eines Eintrags. Nur für Systemmaintainer
     * zugänglich, damit reguläre Backend-Redakteure keine Nutzerdaten ändern
     * können.
     */
    public function editAction(int $uid): ResponseInterface {
        if (!$this->isSystemMaintainer()) {
            return $this->redirectWithError('backend_edit_forbidden');
        }

        $user = $this->tanzpartnersucheRepository->findOneByUidIncludingHidden($uid);

        if ($user === null) {
            return $this->redirectWithError('backend_detail_not_found');
        }

        $this->moduleTemplate->assign('editUser', $user);

        return $this->moduleTemplate->renderResponse('UserAdministration/Edit');
    }

    /**
     * Speichert die in der Bearbeitungsmaske geänderten Stammdaten eines
     * Eintrags. Passwörter werden hier bewusst nicht verarbeitet.
     */
    public function updateAction(
        int $uid,
        string $username,
        string $email,
        int $height,
        int $age,
        int $gender,
        int $role,
        int $category,
        int $level,
        string $bio,
        bool $hidden = false,
    ): ResponseInterface {
        if (!$this->isSystemMaintainer()) {
            return $this->redirectWithError('backend_edit_forbidden');
        }

        $user = $this->tanzpartnersucheRepository->findOneByUidIncludingHidden($uid);

        if ($user === null) {
            return $this->redirectWithError('backend_detail_not_found');
        }

        $username = trim($username);
        $email = trim($email);

        if (!$this->isValidUsername($username)) {
            return $this->redirectToEditWithError($uid, 'error_invalid_username');
        }

        if (!GeneralUtility::validEmail($email) || mb_strlen($email) > 255) {
            return $this->redirectToEditWithError($uid, 'error_invalid_email');
        }

        if (!$this->isInRange($height, 100, 250)) {
            return $this->redirectToEditWithError($uid, 'error_invalid_height');
        }

        if (!$this->isInRange($age, 16, 120)) {
            return $this->redirectToEditWithError($uid, 'error_invalid_age');
        }

        if (!in_array($gender, self::GENDER_VALUES, true)
            || !in_array($role, self::GENDER_VALUES, true)
            || !in_array($category, self::CATEGORY_VALUES, true)
            || !in_array($level, self::LEVEL_VALUES, true)
        ) {
            return $this->redirectToEditWithError($uid, 'error_invalid_selection');
        }

        if (mb_strlen($bio) > self::BIO_MAX_LENGTH) {
            return $this->redirectToEditWithError($uid, 'error_invalid_bio');
        }

        $existingUserWithEmail = $this->tanzpartnersucheRepository->findOneByEmail($email);
        if ($existingUserWithEmail !== null && $existingUserWithEmail->getUid() !== $uid) {
            return $this->redirectToEditWithError($uid, 'error_duplicate_email');
        }

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setHeight($height);
        $user->setAge($age);
        $user->setGender($gender);
        $user->setRole($role);
        $user->setCategory($category);
        $user->setLevel($level);
        $user->setBio($bio);
        $user->setHidden($hidden);
        $user->setChanged(time());

        $this->tanzpartnersucheRepository->update($user);

        $this->addFlashMessage(
            LocalizationUtility::translate(self::LANGUAGE_FILE . 'backend_edit_success') ?? 'Der Eintrag wurde erfolgreich aktualisiert.',
            '',
            ContextualFeedbackSeverity::OK
        );

        return $this->redirect('index');
    }

    /**
     * Löst denselben Passwort-Reset-Mechanismus aus, den ein Nutzer über das
     * Frontend-Formular selbst anstoßen kann: Es wird ein neuer Reset-Code
     * erzeugt und per Mail verschickt. Die Mail weist darauf hin, dass sie
     * auf Wunsch des Nutzers vom Team der GSC München e.V. Tanzpartnersuche
     * versendet wurde.
     */
    public function passwordResetAction(int $uid): ResponseInterface {
        $user = $this->tanzpartnersucheRepository->findOneByUidIncludingHidden($uid);

        if ($user === null) {
            return $this->redirectWithError('backend_detail_not_found');
        }

        $resetPageUid = $this->findResetPageUid();
        if ($resetPageUid === null) {
            return $this->redirectWithError('backend_password_reset_no_page');
        }

        $resetcode = bin2hex(random_bytes(32));
        $user->setResetcode($resetcode);
        $user->setResetcodecreated(time());
        $this->tanzpartnersucheRepository->update($user);

        $this->sendResetMail($resetPageUid, $user->getEmail(), $user->getUsername(), $resetcode);

        $this->addFlashMessage(
            LocalizationUtility::translate(self::LANGUAGE_FILE . 'backend_password_reset_success') ?? 'Die Passwort-Reset-E-Mail wurde verschickt.',
            '',
            ContextualFeedbackSeverity::OK
        );

        return $this->redirect('index');
    }

    /**
     * Ermittelt die Seite, auf der das Tanzpartnersuche-Plugin eingebunden
     * ist, damit im Backend-Kontext (ohne aktuell gerenderte Frontend-Seite)
     * ein gültiger Link zum Passwort-Reset-Formular erzeugt werden kann.
     */
    private function findResetPageUid(): ?int {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();

        $pid = $queryBuilder->select('pid')
            ->from('tt_content')
            ->where($queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter(self::PLUGIN_CTYPE)))
            ->executeQuery()
            ->fetchOne();

        return $pid !== false ? (int)$pid : null;
    }

    private function sendResetMail(int $pageUid, string $email, string $username, string $resetcode): void {
        $site = $this->siteFinder->getSiteByPageId($pageUid);
        $resetUri = $site->getRouter()->generateUri(
            $pageUid,
            [
                'tx_tanzpartnersuche_tanzpartnersuche' => [
                    'action' => 'newpassword',
                    'controller' => 'Tanzpartnersuche',
                    'email' => $email,
                    'token' => $resetcode,
                ],
            ]
        );

        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $mail->setTo($email)
            ->setSubject(LocalizationUtility::translate(self::LANGUAGE_FILE . 'mail_reset_subject') ?? 'Tanzpartnersuche: Passwort zurücksetzen')
            ->text(sprintf(
                "Hallo %s,\n\ndiese E-Mail wurde auf Deinen Wunsch vom Team der GSC München e.V. Tanzpartnersuche verschickt.\n\nÜber folgenden Link kannst Du Dein Passwort zurücksetzen:\n\n%s\n\nDer Link ist %d Stunden gültig. Falls Du das nicht angefordert hast, ignoriere diese E-Mail.",
                $username,
                (string)$resetUri,
                (int)(self::RESET_VALIDITY_SECONDS / 3600)
            ));
        $this->mailer->send($mail);
    }

    private function isSystemMaintainer(): bool {
        return (bool)($GLOBALS['BE_USER']?->isSystemMaintainer() ?? false);
    }

    private function isValidUsername(string $username): bool {
        $length = mb_strlen($username);
        return $length >= self::USERNAME_MIN_LENGTH
            && $length <= self::USERNAME_MAX_LENGTH
            && preg_match('/^[\p{L}\p{N} _.\-]+$/u', $username) === 1;
    }

    private function isInRange(int $value, int $min, int $max): bool {
        return $value >= $min && $value <= $max;
    }

    private function redirectToEditWithError(int $uid, string $translationKey): ResponseInterface {
        $this->addFlashMessage(
            LocalizationUtility::translate(self::LANGUAGE_FILE . $translationKey) ?? $translationKey,
            '',
            ContextualFeedbackSeverity::ERROR
        );

        return $this->redirect('edit', null, null, ['uid' => $uid]);
    }

    private function redirectWithError(string $translationKey): ResponseInterface {
        $this->addFlashMessage(
            LocalizationUtility::translate(self::LANGUAGE_FILE . $translationKey) ?? $translationKey,
            '',
            ContextualFeedbackSeverity::ERROR
        );

        return $this->redirect('index');
    }

    /**
     * Löscht einen Eintrag unwiderruflich (echtes SQL-DELETE, kein Soft-Delete
     * über das TCA-"deleted"-Flag), da im Backend-Modul ein vollständiges,
     * endgültiges Entfernen gefordert ist.
     */
    public function deleteAction(int $uid): ResponseInterface {
        $this->connectionPool
            ->getConnectionForTable(self::TABLE_NAME)
            ->delete(self::TABLE_NAME, ['uid' => $uid]);

        $this->addFlashMessage(
            LocalizationUtility::translate(self::LANGUAGE_FILE . 'backend_delete_success') ?? 'Der Eintrag wurde unwiderruflich gelöscht.',
            '',
            ContextualFeedbackSeverity::OK
        );

        return $this->redirect('index');
    }
}
