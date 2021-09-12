<?php
declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Peter-Benedikt von Niebelschütz <ias@gsc-muenchen.de>
 * @author Martin Arend <ias@gsc-muenchen.de>
 */
class UserTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Domain\Model\User
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \GSC\Tanzpartnersuche\Domain\Model\User();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getUsernameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getUsername()
        );
    }

    /**
     * @test
     */
    public function setUsernameForStringSetsUsername()
    {
        $this->subject->setUsername('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'username',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPasswordReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function setPasswordForStringSetsPassword()
    {
        $this->subject->setPassword('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'password',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getHeightReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getHeight()
        );
    }

    /**
     * @test
     */
    public function setHeightForIntSetsHeight()
    {
        $this->subject->setHeight(12);

        self::assertAttributeEquals(
            12,
            'height',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAgeReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getAge()
        );
    }

    /**
     * @test
     */
    public function setAgeForIntSetsAge()
    {
        $this->subject->setAge(12);

        self::assertAttributeEquals(
            12,
            'age',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getGenderReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderForStringSetsGender()
    {
        $this->subject->setGender('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'gender',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPictureReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getPicture()
        );
    }

    /**
     * @test
     */
    public function setPictureForFileReferenceSetsPicture()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setPicture($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'picture',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRelOfferReturnsInitialValueForOffer()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRelOffer()
        );
    }

    /**
     * @test
     */
    public function setRelOfferForObjectStorageContainingOfferSetsRelOffer()
    {
        $relOffer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();
        $objectStorageHoldingExactlyOneRelOffer = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneRelOffer->attach($relOffer);
        $this->subject->setRelOffer($objectStorageHoldingExactlyOneRelOffer);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneRelOffer,
            'relOffer',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addRelOfferToObjectStorageHoldingRelOffer()
    {
        $relOffer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();
        $relOfferObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $relOfferObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($relOffer));
        $this->inject($this->subject, 'relOffer', $relOfferObjectStorageMock);

        $this->subject->addRelOffer($relOffer);
    }

    /**
     * @test
     */
    public function removeRelOfferFromObjectStorageHoldingRelOffer()
    {
        $relOffer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();
        $relOfferObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $relOfferObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($relOffer));
        $this->inject($this->subject, 'relOffer', $relOfferObjectStorageMock);

        $this->subject->removeRelOffer($relOffer);
    }
}
