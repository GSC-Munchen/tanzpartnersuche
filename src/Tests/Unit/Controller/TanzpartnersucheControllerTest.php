<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Martin Arend <tanzpartnersuche@gsc-muenchen.de>
 */
class TanzpartnersucheControllerTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Controller\TanzpartnersucheController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\GSC\Tanzpartnersuche\Controller\TanzpartnersucheController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenTanzpartnersucheToView(): void
    {
        $tanzpartnersuche = new \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('tanzpartnersuche', $tanzpartnersuche);

        $this->subject->showAction($tanzpartnersuche);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenTanzpartnersucheToTanzpartnersucheRepository(): void
    {
        $tanzpartnersuche = new \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche();

        $tanzpartnersucheRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $tanzpartnersucheRepository->expects(self::once())->method('add')->with($tanzpartnersuche);
        $this->subject->_set('tanzpartnersucheRepository', $tanzpartnersucheRepository);

        $this->subject->createAction($tanzpartnersuche);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenTanzpartnersucheToView(): void
    {
        $tanzpartnersuche = new \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('tanzpartnersuche', $tanzpartnersuche);

        $this->subject->editAction($tanzpartnersuche);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenTanzpartnersucheInTanzpartnersucheRepository(): void
    {
        $tanzpartnersuche = new \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche();

        $tanzpartnersucheRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $tanzpartnersucheRepository->expects(self::once())->method('update')->with($tanzpartnersuche);
        $this->subject->_set('tanzpartnersucheRepository', $tanzpartnersucheRepository);

        $this->subject->updateAction($tanzpartnersuche);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenTanzpartnersucheFromTanzpartnersucheRepository(): void
    {
        $tanzpartnersuche = new \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche();

        $tanzpartnersucheRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $tanzpartnersucheRepository->expects(self::once())->method('remove')->with($tanzpartnersuche);
        $this->subject->_set('tanzpartnersucheRepository', $tanzpartnersucheRepository);

        $this->subject->deleteAction($tanzpartnersuche);
    }
}
