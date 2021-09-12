<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Domain\Model;


/**
 * This file is part of the "Tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Peter-Benedikt von Niebelschütz <ias@gsc-muenchen.de>, GSC München e.V.
 *          Martin Arend <ias@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * Main
 */
class Main extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * relUser
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\User>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $relUser = null;

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->relUser = $this->relUser ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a User
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $relUser
     * @return void
     */
    public function addRelUser(\GSC\Tanzpartnersuche\Domain\Model\User $relUser)
    {
        $this->relUser->attach($relUser);
    }

    /**
     * Removes a User
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $relUserToRemove The User to be removed
     * @return void
     */
    public function removeRelUser(\GSC\Tanzpartnersuche\Domain\Model\User $relUserToRemove)
    {
        $this->relUser->detach($relUserToRemove);
    }

    /**
     * Returns the relUser
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\User> $relUser
     */
    public function getRelUser()
    {
        return $this->relUser;
    }

    /**
     * Sets the relUser
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\User> $relUser
     * @return void
     */
    public function setRelUser(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relUser)
    {
        $this->relUser = $relUser;
    }
}
