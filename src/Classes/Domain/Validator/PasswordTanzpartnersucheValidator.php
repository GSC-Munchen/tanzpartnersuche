<?php
namespace GSC\Tanzpartnersuche\Domain\Validator;

class PasswordTanzpartnersucheValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
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
        // if password is empty or not >8 and <50 characters long, throw error
        if ((strlen($loginTanzpartnersuche->getPassword()) < 8) || (strlen($loginTanzpartnersuche->getPassword()) > 50)) {
            $this->result->forProperty('password')->addError(new \TYPO3\CMS\Extbase\Error\Error('Das Passwort ist nicht zwischen 8 und 50 Zeichen lang.', 1655749870));
        }
        
        // if passwords do not match, throw error
        if (($loginTanzpartnersuche->getPassword()) !== ($loginTanzpartnersuche->getPasswordconfirmation())) {
            $this->result->forProperty('passwordconfirmation')->addError(new \TYPO3\CMS\Extbase\Error\Error('Die eingegebenen Passwörter stimmen nicht überein.', 1655749852));
        }

    }
}