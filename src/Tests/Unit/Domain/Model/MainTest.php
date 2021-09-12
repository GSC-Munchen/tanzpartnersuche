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
class MainTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Domain\Model\Main
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \GSC\Tanzpartnersuche\Domain\Model\Main();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getRelUserReturnsInitialValueForUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRelUser()
        );
    }

    /**
     * @test
     */
    public function setRelUserForObjectStorageContainingUserSetsRelUser()
    {
        $relUser = new \GSC\Tanzpartnersuche\Domain\Model\User();
        $objectStorageHoldingExactlyOneRelUser = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneRelUser->attach($relUser);
        $this->subject->setRelUser($objectStorageHoldingExactlyOneRelUser);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneRelUser,
            'relUser',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addRelUserToObjectStorageHoldingRelUser()
    {
        $relUser = new \GSC\Tanzpartnersuche\Domain\Model\User();
        $relUserObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $relUserObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($relUser));
        $this->inject($this->subject, 'relUser', $relUserObjectStorageMock);

        $this->subject->addRelUser($relUser);
    }

    /**
     * @test
     */
    public function removeRelUserFromObjectStorageHoldingRelUser()
    {
        $relUser = new \GSC\Tanzpartnersuche\Domain\Model\User();
        $relUserObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $relUserObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($relUser));
        $this->inject($this->subject, 'relUser', $relUserObjectStorageMock);

        $this->subject->removeRelUser($relUser);
    }
}
