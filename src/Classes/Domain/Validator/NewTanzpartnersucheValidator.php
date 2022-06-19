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
        if ($this->tanzpartnersucheRepository->findUserByEmail($newTanzpartnersuche->getEmail()) != NULL) {
            $this->addError('Diese Mailadresse wurde bereits registriert. Bitte eine andere verwenden oder bestehenden Eintrag editieren/löschen. Ggfs. Passwort vergessen Funktion nutzen.', 1221563773);
        }
        
        if ($newTanzpartnersuche->getGender() == '0') {
            $this->addError('Bitte gib Dein Geschlecht an.', 1262341710);
        }

        if ($newTanzpartnersuche->getLevel() == '0') {
            $this->addError('Bitte gib Dein Tanzniveau an.', 1262341711);
        }

        if ($newTanzpartnersuche->getCategory() == '0') {
            $this->addError('Bitte gib Deine gewünschte Disziplin an.', 1262341712);
        }

        if ($newTanzpartnersuche->getRole() == '0') {
            $this->addError('Bitte gib an, welche Rolle du suchst.', 1262341714);
        }
    }
}