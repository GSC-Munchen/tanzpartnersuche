<?php
declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Peter von Niebelschütz <ias@gsc-muenchen.de>
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
    public function getRelationUserReturnsInitialValueForUser()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getRelationUser()
        );
    }

    /**
     * @test
     */
    public function setRelationUserForObjectStorageContainingUserSetsRelationUser()
    {
        $relationUser = new \GSC\Tanzpartnersuche\Domain\Model\User();
        $objectStorageHoldingExactlyOneRelationUser = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneRelationUser->attach($relationUser);
        $this->subject->setRelationUser($objectStorageHoldingExactlyOneRelationUser);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneRelationUser,
            'relationUser',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addRelationUserToObjectStorageHoldingRelationUser()
    {
        $relationUser = new \GSC\Tanzpartnersuche\Domain\Model\User();
        $relationUserObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $relationUserObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($relationUser));
        $this->inject($this->subject, 'relationUser', $relationUserObjectStorageMock);

        $this->subject->addRelationUser($relationUser);
    }

    /**
     * @test
     */
    public function removeRelationUserFromObjectStorageHoldingRelationUser()
    {
        $relationUser = new \GSC\Tanzpartnersuche\Domain\Model\User();
        $relationUserObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $relationUserObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($relationUser));
        $this->inject($this->subject, 'relationUser', $relationUserObjectStorageMock);

        $this->subject->removeRelationUser($relationUser);
    }
}
