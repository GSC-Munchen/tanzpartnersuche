<?php

namespace GSC\Tanzpartnersuche\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class User extends AbstractEntity {
    protected $username = '';

    protected $email = '';

    public function __construct(string $username = '', string $email = ''): void {
        $this->setUsername($username);
        $this->setEmail($email);
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }
}
