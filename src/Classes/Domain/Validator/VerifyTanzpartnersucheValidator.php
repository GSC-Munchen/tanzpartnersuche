<?php
namespace GSC\Tanzpartnersuche\Domain\Validator;

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
        // check if username is already in database
        if ($this->tanzpartnersucheRepository->findUserByUsername($verifyTanzpartnersuche->getUsername()) == NULL) {
            $this->addError('Der eingegebene Username ist ungültig.', 1655746851);
        }        

        // ToDo: check if validation code is matching to username

        // ToDo: check if verification was already done

        // ToDo: check if verification code is still valid (max 48hours)
    }
}