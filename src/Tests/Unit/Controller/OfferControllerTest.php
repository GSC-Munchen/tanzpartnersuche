<?php
declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Tests\Unit\Controller;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Peter-Benedikt von Niebelschütz <ias@gsc-muenchen.de>
 * @author Martin Arend <ias@gsc-muenchen.de>
 */
class OfferControllerTest extends UnitTestCase
{
    /**
     * @var \GSC\Tanzpartnersuche\Controller\OfferController
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\GSC\Tanzpartnersuche\Controller\OfferController::class)
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
    public function listActionFetchesAllOffersFromRepositoryAndAssignsThemToView()
    {
        $allOffers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $offerRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\OfferRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $offerRepository->expects(self::once())->method('findAll')->will(self::returnValue($allOffers));
        $this->inject($this->subject, 'offerRepository', $offerRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('offers', $allOffers);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenOfferToView()
    {
        $offer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('offer', $offer);

        $this->subject->showAction($offer);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenOfferToOfferRepository()
    {
        $offer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();

        $offerRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\OfferRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $offerRepository->expects(self::once())->method('add')->with($offer);
        $this->inject($this->subject, 'offerRepository', $offerRepository);

        $this->subject->createAction($offer);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenOfferToView()
    {
        $offer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('offer', $offer);

        $this->subject->editAction($offer);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenOfferInOfferRepository()
    {
        $offer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();

        $offerRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\OfferRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $offerRepository->expects(self::once())->method('update')->with($offer);
        $this->inject($this->subject, 'offerRepository', $offerRepository);

        $this->subject->updateAction($offer);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenOfferFromOfferRepository()
    {
        $offer = new \GSC\Tanzpartnersuche\Domain\Model\Offer();

        $offerRepository = $this->getMockBuilder(\GSC\Tanzpartnersuche\Domain\Repository\OfferRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $offerRepository->expects(self::once())->method('remove')->with($offer);
        $this->inject($this->subject, 'offerRepository', $offerRepository);

        $this->subject->deleteAction($offer);
    }
}
