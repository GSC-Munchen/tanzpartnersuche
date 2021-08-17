<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Domain\Model;


/**
 * This file is part of the "Tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Peter von Niebelschütz <ias@gsc-muenchen.de>
 *          Martin Arend <ias@gsc-muenchen.de>
 */

/**
 * Main Module
 */
class Main extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * relationUser
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\User>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $relationUser = null;

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
        $this->relationUser = $this->relationUser ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a User
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $relationUser
     * @return void
     */
    public function addRelationUser(\GSC\Tanzpartnersuche\Domain\Model\User $relationUser)
    {
        $this->relationUser->attach($relationUser);
    }

    /**
     * Removes a User
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $relationUserToRemove The User to be removed
     * @return void
     */
    public function removeRelationUser(\GSC\Tanzpartnersuche\Domain\Model\User $relationUserToRemove)
    {
        $this->relationUser->detach($relationUserToRemove);
    }

    /**
     * Returns the relationUser
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\User> $relationUser
     */
    public function getRelationUser()
    {
        return $this->relationUser;
    }

    /**
     * Sets the relationUser
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\User> $relationUser
     * @return void
     */
    public function setRelationUser(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relationUser)
    {
        $this->relationUser = $relationUser;
    }
}
