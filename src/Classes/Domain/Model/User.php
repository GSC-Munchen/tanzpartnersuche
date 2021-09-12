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
 * User Domain
 */
class User extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * username
     *
     * @var string
     */
    protected $username = '';

    /**
     * password
     *
     * @var string
     */
    protected $password = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * height
     *
     * @var int
     */
    protected $height = 0;

    /**
     * age
     *
     * @var int
     */
    protected $age = 0;

    /**
     * gender
     *
     * @var string
     */
    protected $gender = '';

    /**
     * picture
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $picture = null;

    /**
     * relOffer
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\Offer>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $relOffer = null;

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
        $this->relOffer = $this->relOffer ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the username
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * Returns the password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Returns the height
     *
     * @return int $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Sets the height
     *
     * @param int $height
     * @return void
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    /**
     * Returns the age
     *
     * @return int $age
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Sets the age
     *
     * @param int $age
     * @return void
     */
    public function setAge(int $age)
    {
        $this->age = $age;
    }

    /**
     * Returns the gender
     *
     * @return string $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the gender
     *
     * @param string $gender
     * @return void
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * Returns the picture
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Sets the picture
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $picture
     * @return void
     */
    public function setPicture(\TYPO3\CMS\Extbase\Domain\Model\FileReference $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Adds a Offer
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $relOffer
     * @return void
     */
    public function addRelOffer(\GSC\Tanzpartnersuche\Domain\Model\Offer $relOffer)
    {
        $this->relOffer->attach($relOffer);
    }

    /**
     * Removes a Offer
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $relOfferToRemove The Offer to be removed
     * @return void
     */
    public function removeRelOffer(\GSC\Tanzpartnersuche\Domain\Model\Offer $relOfferToRemove)
    {
        $this->relOffer->detach($relOfferToRemove);
    }

    /**
     * Returns the relOffer
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\Offer> $relOffer
     */
    public function getRelOffer()
    {
        return $this->relOffer;
    }

    /**
     * Sets the relOffer
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GSC\Tanzpartnersuche\Domain\Model\Offer> $relOffer
     * @return void
     */
    public function setRelOffer(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relOffer)
    {
        $this->relOffer = $relOffer;
    }
}
