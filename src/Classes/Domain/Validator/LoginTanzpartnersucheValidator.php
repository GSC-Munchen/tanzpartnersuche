<?php
namespace GSC\Tanzpartnersuche\Domain\Validator;


/**
 * Verification of all Inputs and crosscheck with database
 */
class LoginTanzpartnersucheValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
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
    
    protected function isValid($loginTanzpartnersuche)
    {
        // if login credentials are not valid, throw error
        if ($this->tanzpartnersucheRepository->verifyUserCredentials($loginTanzpartnersuche->getUsername(), $loginTanzpartnersuche->getPassword()) === NULL) {
            $this->result->forProperty('username')->addError(new \TYPO3\CMS\Extbase\Error\Error('Deine Eingaben sind ungültig. Bitte überprüfe diese.', 1655749851));
        }

        // if password is empty or not >8 and <50 characters long, throw error
        if ((strlen($loginTanzpartnersuche->getPassword()) < 8) || (strlen($loginTanzpartnersuche->getPassword()) > 50)) {
            $this->result->forProperty('password')->addError(new \TYPO3\CMS\Extbase\Error\Error('Das Passwort ist nicht zwischen 8 und 50 Zeichen lang.', 1655749870));
        }
    }
}