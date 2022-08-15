<?php
namespace GSC\Tanzpartnersuche\Domain\Validator;

class NewTanzpartnersucheValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
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
    
    protected function isValid($newTanzpartnersuche)
    {
        // if username is already registered, throw error
        if ($this->tanzpartnersucheRepository->findUserByUsername($newTanzpartnersuche->getUsername()) !== NULL) {
            $this->result->forProperty('username')->addError(new \TYPO3\CMS\Extbase\Error\Error('Dieser Benutzername wurde bereits registriert. Bitte einen anderen verwenden oder bestehenden Eintrag editieren/löschen.', 1655749342));
        }
        
        // if mail is already registered, throw error
        if ($this->tanzpartnersucheRepository->findUserByEmail($newTanzpartnersuche->getEmail()) !== NULL) {
            $this->result->forProperty('email')->addError(new \TYPO3\CMS\Extbase\Error\Error('Diese Mailadresse wurde bereits registriert. Bitte eine andere verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', 1655749851));
        }

        // if password is empty or not >8 and <50 characters long, throw error
        if ((strlen($newTanzpartnersuche->getPassword()) < 8) || (strlen($newTanzpartnersuche->getPassword()) > 50)) {
            $this->result->forProperty('password')->addError(new \TYPO3\CMS\Extbase\Error\Error('Das Passwort ist nicht zwischen 8 und 50 Zeichen lang.', 1655749870));
        }
        
        // if passwords do not match, throw error
        if (($newTanzpartnersuche->getPassword()) !== ($newTanzpartnersuche->getPasswordconfirmation())) {
            $this->result->forProperty('password')->addError(new \TYPO3\CMS\Extbase\Error\Error('Die eingegebenen Passwörter stimmen nicht überein.', 1655749852));
        }
        
        // if email formait is invalid, throw error
        if (($newTanzpartnersuche->getEmail()) !== ($newTanzpartnersuche->getEmail())) {
            if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email))
            { 
                $this->result->forProperty('email')->addError(new \TYPO3\CMS\Extbase\Error\Error('Das E-Mail Format ist nicht gültig.', 1655749871));
            }
        }

        // if gender is not selected (but default value), throw error
        if ($newTanzpartnersuche->getGender() == '0') {
            $this->result->forProperty('gender')->addError(new \TYPO3\CMS\Extbase\Error\Error('Bitte gib Dein Geschlecht an.', 1655749853));
        }

        // if level is not selected (but default value), throw error
        if ($newTanzpartnersuche->getLevel() == '0') {
            $this->result->forProperty('level')->addError(new \TYPO3\CMS\Extbase\Error\Error('Bitte gib Dein Tanzniveau an.', 1655749854));
        }

        // if category is not selected (but default value), throw error
        if ($newTanzpartnersuche->getCategory() == '0') {
            $this->result->forProperty('category')->addError(new \TYPO3\CMS\Extbase\Error\Error('Bitte gib Deine gewünschte Disziplin an.', 1655749855));
        }

        // if role is not selected (but default value), throw error
        if ($newTanzpartnersuche->getRole() == '0') {
            $this->result->forProperty('role')->addError(new \TYPO3\CMS\Extbase\Error\Error('Bitte gib an, welche Rolle du suchst.', 1655749856));
        }

        // if height is empty or not >85 and <220 cm, throw error
        if (($newTanzpartnersuche->getHeight() < 85) || ($newTanzpartnersuche->getHeight() > 220)) {
            $this->result->forProperty('height')->addError(new \TYPO3\CMS\Extbase\Error\Error('Der Wert liegt nicht zwischen 85 und 220cm.', 1655749872));
        }

        // if age is empty or not >18 and <99 years, throw error
        if (($newTanzpartnersuche->getAge() < 18) || ($newTanzpartnersuche->getAge() > 99)) {
            $this->result->forProperty('age')->addError(new \TYPO3\CMS\Extbase\Error\Error('Der Wert liegt nicht zwischen 18 und 99 Jahren.', 1655749873));
        }

    }
}