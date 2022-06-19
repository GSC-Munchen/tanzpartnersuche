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
        
        if ($newTanzpartnersuche->getPassword() == 'test') {
            $this->addError('Schwaches Passwort.', 1262341709);
        }
    }
}