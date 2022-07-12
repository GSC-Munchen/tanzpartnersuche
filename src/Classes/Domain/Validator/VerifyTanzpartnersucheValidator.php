<?php
namespace GSC\Tanzpartnersuche\Domain\Validator;

/**
 * Verification of all Inputs and crosscheck with database
 */
class VerifyTanzpartnersucheValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
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
    
    protected function isValid($verifyTanzpartnersuche)
    {
        // check if verification code is a 24digit number
        if (!is_numeric($verifyTanzpartnersuche->getVerificationcode()) || (strlen((string)$verifyTanzpartnersuche->getVerificationcode()) !== 24)) {
            $this->result->forProperty('verificationcode')->addError(new \TYPO3\CMS\Extbase\Error\Error('Der Validierungscode ist ungültig. Bitte überprüfen.', 1455746854));
            return;
        }

        // check if verification was already done
        if ($this->tanzpartnersucheRepository->UserValidationCheck($verifyTanzpartnersuche->getUsername(), $verifyTanzpartnersuche->getVerificationcode()) !== NULL) {
            $this->result->forProperty('username')->addError(new \TYPO3\CMS\Extbase\Error\Error('Der Eintrag wurde bereits erfolgreich freigeschaltet.', 1655746852));
            return;
        }

        // check if verification code is still valid (max 48hours) - could only happen if expires earlier than database was cleaned up
        $vc = $verifyTanzpartnersuche->getVerificationcode();
        $timestamp = mktime (substr($vc,12,2), substr($vc,16,2), substr($vc,20,2), substr($vc,4,2), substr($vc,8,2), substr($vc,0,2)) + 172800;  // +172800 adds 48h
        if ($timestamp <= time()) {
            $this->result->forProperty('verificationcode')->addError(new \TYPO3\CMS\Extbase\Error\Error('Der Validierungscode ist nicht mehr gültig. Bitte erneut registrieren.', 1455746853));
            return;
        }

        // check if username/password combination is in database
        if ($this->tanzpartnersucheRepository->findUserByValidation($verifyTanzpartnersuche->getUsername(), $verifyTanzpartnersuche->getVerificationcode()) == NULL) {
            $this->result->forProperty('username')->addError(new \TYPO3\CMS\Extbase\Error\Error('Die eingegebene Kombination aus Benutzername und Verificationscode ist ungültig. Bitte überprüfen.', 1655746851));
            $this->result->forProperty('verificationcode')->addError(new \TYPO3\CMS\Extbase\Error\Error('Die eingegebene Kombination aus Benutzername und Verificationscode ist ungültig. Bitte überprüfen.', 1655746858));
            return;
        }
    }
}