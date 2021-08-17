<?php
declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Tests\Unit\Controller;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Peter von Niebelschütz <ias@gsc-muenchen.de>
 * @author Martin Arend <ias@gsc-muenchen.de>
 */
class MainControllerTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Controller\MainController
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\GSC\Tanzpartnersuche\Controller\MainController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllMainsFromRepositoryAndAssignsThemToView()
    {
        $allMains = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mainRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\MainRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $mainRepository->expects(self::once())->method('findAll')->will(self::returnValue($allMains));
        $this->inject($this->subject, 'mainRepository', $mainRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('mains', $allMains);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenMainToView()
    {
        $main = new \GSC\Tanzpartnersuche\Domain\Model\Main();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('main', $main);

        $this->subject->showAction($main);
    }
}
