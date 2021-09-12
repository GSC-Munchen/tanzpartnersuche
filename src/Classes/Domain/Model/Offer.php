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
 * Offer Domain
 */
class Offer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

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
}
