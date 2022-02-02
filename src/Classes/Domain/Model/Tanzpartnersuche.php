<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Domain\Model;


/**
 * This file is part of the "tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Martin Arend <tanzpartnersuche@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * Main
 */
class Tanzpartnersuche extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * level
     *
     * @var string
     */
    protected $level = '';

    /**
     * category
     *
     * @var string
     */
    protected $category = '';

    /**
     * bio
     *
     * @var string
     */
    protected $bio = '';

    /**
     * role
     *
     * @var string
     */
    protected $role = '';

    /**
     * verificationcode
     *
     * @var string
     */
    protected $verificationcode = '';

    /**
     * loggedin
     *
     * @var string
     */
    protected $loggedin = '';

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
     * Returns the level
     *
     * @return string $level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Sets the level
     *
     * @param string $level
     * @return void
     */
    public function setLevel(string $level)
    {
        $this->level = $level;
    }

    /**
     * Returns the category
     *
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param string $category
     * @return void
     */
    public function setCategory(string $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the bio
     *
     * @return string $bio
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Sets the bio
     *
     * @param string $bio
     * @return void
     */
    public function setBio(string $bio)
    {
        $this->bio = $bio;
    }

    /**
     * Returns the role
     *
     * @return string $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets the role
     *
     * @param string $role
     * @return void
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * Returns the verificationcode
     *
     * @return string $verificationcode
     */
    public function getVerificationcode()
    {
        return $this->verificationcode;
    }

    /**
     * Sets the verificationcode
     *
     * @param string $verificationcode
     * @return void
     */
    public function setVerificationcode(string $verificationcode)
    {
        $this->verificationcode = $verificationcode;
    }

    /**
     * Returns the loggedin
     *
     * @return string $loggedin
     */
    public function getLoggedin()
    {
        return $this->loggedin;
    }

    /**
     * Sets the loggedin
     *
     * @param string $loggedin
     * @return void
     */
    public function setLoggedin(string $loggedin)
    {
        $this->loggedin = $loggedin;
    }

    /**
     * Returns the hidden-flag
     *
     * @return string $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets the hidden-flag
     *
     * @param string $hidden
     * @return void
     */
    public function setHidden(string $hidden)
    {
        $this->hidden = $hidden;
    }
}
