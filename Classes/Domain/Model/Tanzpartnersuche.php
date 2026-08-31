<?php

namespace Gsc\Tanzpartnersuche\Domain\Model;

use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Tanzpartnersuche extends AbstractEntity
{
    protected string $username = '';
    protected string $password = '';
    protected string $passwordconfirmation = '';
    protected string $email = '';
    protected int $height = 0;
    protected int $age = 0;
    protected int $gender = 0;
    protected int $level = 0;
    protected int $category = 0;
    protected string $bio = '';
    protected int $role = 0;
    protected string $verificationcode = '';
    protected int $loggedin = 0;
    protected int $created = 0;
    protected int $changed = 0;
    protected bool $hidden = false;
    protected string $resetcode = '';
    protected int $resetcodecreated = 0;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username; 
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password; 
    }

    public function getPasswordconfirmation(): string
    {
        return $this->passwordconfirmation;
    }

    public function setPasswordconfirmation(string $passwordconfirmation): void
    {
        $this->passwordconfirmation = $passwordconfirmation; 
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email; 
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height; 
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age; 
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function setGender(int $gender): void
    {
        $this->gender = $gender; 
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level; 
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category; 
    }

    public function getBio(): string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio; 
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): void
    {
        $this->role = $role; 
    }

    public function getVerificationcode(): string
    {
        return $this->verificationcode;
    }

    public function setVerificationcode(string $verificationcode): void
    {
        $this->verificationcode = $verificationcode; 
    }

    public function getLoggedin(): int
    {
        return $this->loggedin;
    }

    public function setLoggedin(int $loggedin): void
    {
        $this->loggedin = $loggedin; 
    }

    public function getCreated(): int
    {
        return $this->created;
    }

    public function setCreated(int $created): void
    {
        $this->created = $created;
    }

    public function getChanged(): int
    {
        return $this->changed;
    }

    public function setChanged(int $changed): void
    {
        $this->changed = $changed;
    }

    /**
     * Frontend-Einträge bleiben genau 6 Monate ab der letzten Änderung sichtbar,
     * danach werden sie im Frontend nicht mehr angezeigt.
     */
    public function getVisibleUntil(): int
    {
        $visibleUntil = (new \DateTime())->setTimestamp($this->changed);
        $visibleUntil->modify('+6 months');

        return $visibleUntil->getTimestamp();
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function getResetcode(): string
    {
        return $this->resetcode;
    }

    public function setResetcode(string $resetcode): void
    {
        $this->resetcode = $resetcode;
    }

    public function getResetcodecreated(): int
    {
        return $this->resetcodecreated;
    }

    public function setResetcodecreated(int $resetcodecreated): void
    {
        $this->resetcodecreated = $resetcodecreated;
    }

}