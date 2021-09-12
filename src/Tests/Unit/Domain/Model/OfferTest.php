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
class OfferTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Domain\Model\Offer
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \GSC\Tanzpartnersuche\Domain\Model\Offer();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getLevelReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function setLevelForStringSetsLevel()
    {
        $this->subject->setLevel('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'level',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForStringSetsCategory()
    {
        $this->subject->setCategory('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'category',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getBioReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getBio()
        );
    }

    /**
     * @test
     */
    public function setBioForStringSetsBio()
    {
        $this->subject->setBio('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'bio',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRoleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getRole()
        );
    }

    /**
     * @test
     */
    public function setRoleForStringSetsRole()
    {
        $this->subject->setRole('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'role',
            $this->subject
        );
    }
}
