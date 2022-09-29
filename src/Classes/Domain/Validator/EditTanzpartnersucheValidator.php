<?php
namespace GSC\Tanzpartnersuche\Domain\Validator;

class EditTanzpartnersucheValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
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