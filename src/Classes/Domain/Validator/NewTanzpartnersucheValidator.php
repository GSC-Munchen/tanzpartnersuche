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
        if ($this->tanzpartnersucheRepository->findUserByEmail($newTanzpartnersuche->getEmail()) !== NULL) {
            $this->addError('Diese Mailadresse wurde bereits registriert. Bitte eine andere verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', 1655749851);
        }

        if (($newTanzpartnersuche->getPassword()) !== ($newTanzpartnersuche->getPasswordconfirmation())) {
            $this->addError('Die eingegebenen Passwörter stimmen nicht überein.', 1655749852);
        }
        
        if ($newTanzpartnersuche->getGender() == '0') {
            $this->addError('Bitte gib Dein Geschlecht an.', 1655749853);
        }

        if ($newTanzpartnersuche->getLevel() == '0') {
            $this->addError('Bitte gib Dein Tanzniveau an.', 1655749854);
        }

        if ($newTanzpartnersuche->getCategory() == '0') {
            $this->addError('Bitte gib Deine gewünschte Disziplin an.', 1655749855);
        }

        if ($newTanzpartnersuche->getRole() == '0') {
            $this->addError('Bitte gib an, welche Rolle du suchst.', 1655749856);
        }
    }
}