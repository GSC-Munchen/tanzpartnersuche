<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Martin Arend <tanzpartnersuche@gsc-muenchen.de>
 */
class TanzpartnersucheTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getUsernameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getUsername()
        );
    }

    /**
     * @test
     */
    public function setUsernameForStringSetsUsername(): void
    {
        $this->subject->setUsername('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('username'));
    }

    /**
     * @test
     */
    public function getPasswordReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function setPasswordForStringSetsPassword(): void
    {
        $this->subject->setPassword('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('password'));
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail(): void
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('email'));
    }

    /**
     * @test
     */
    public function getHeightReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getHeight()
        );
    }

    /**
     * @test
     */
    public function setHeightForIntSetsHeight(): void
    {
        $this->subject->setHeight(12);

        self::assertEquals(12, $this->subject->_get('height'));
    }

    /**
     * @test
     */
    public function getAgeReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getAge()
        );
    }

    /**
     * @test
     */
    public function setAgeForIntSetsAge(): void
    {
        $this->subject->setAge(12);

        self::assertEquals(12, $this->subject->_get('age'));
    }

    /**
     * @test
     */
    public function getGenderReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderForStringSetsGender(): void
    {
        $this->subject->setGender('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('gender'));
    }

    /**
     * @test
     */
    public function getPictureReturnsInitialValueForFileReference(): void
    {
        self::assertEquals(
            null,
            $this->subject->getPicture()
        );
    }

    /**
     * @test
     */
    public function setPictureForFileReferenceSetsPicture(): void
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setPicture($fileReferenceFixture);

        self::assertEquals($fileReferenceFixture, $this->subject->_get('picture'));
    }

    /**
     * @test
     */
    public function getLevelReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function setLevelForStringSetsLevel(): void
    {
        $this->subject->setLevel('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('level'));
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForStringSetsCategory(): void
    {
        $this->subject->setCategory('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('category'));
    }

    /**
     * @test
     */
    public function getBioReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getBio()
        );
    }

    /**
     * @test
     */
    public function setBioForStringSetsBio(): void
    {
        $this->subject->setBio('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('bio'));
    }

    /**
     * @test
     */
    public function getRoleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getRole()
        );
    }

    /**
     * @test
     */
    public function setRoleForStringSetsRole(): void
    {
        $this->subject->setRole('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('role'));
    }

    /**
     * @test
     */
    public function getVerificationcodeReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getVerificationcode()
        );
    }

    /**
     * @test
     */
    public function setVerificationcodeForStringSetsVerificationcode(): void
    {
        $this->subject->setVerificationcode('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('verificationcode'));
    }

    /**
     * @test
     */
    public function getLoggedinReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLoggedin()
        );
    }

    /**
     * @test
     */
    public function setLoggedinForStringSetsLoggedin(): void
    {
        $this->subject->setLoggedin('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('loggedin'));
    }
}
