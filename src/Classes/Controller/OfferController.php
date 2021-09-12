<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;


/**
 * This file is part of the "Tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Peter-Benedikt von Niebelschütz <ias@gsc-muenchen.de>, GSC München e.V.
 *          Martin Arend <ias@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * OfferController
 */
class OfferController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * offerRepository
     *
     * @var \GSC\Tanzpartnersuche\Domain\Repository\OfferRepository
     */
    protected $offerRepository = null;

    /**
     * action index
     *
     * @return string|object|null|void
     */
    public function indexAction()
    {
    }

    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $offers = $this->offerRepository->findAll();
        $this->view->assign('offers', $offers);
    }

    /**
     * action show
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $offer
     * @return string|object|null|void
     */
    public function showAction(\GSC\Tanzpartnersuche\Domain\Model\Offer $offer)
    {
        $this->view->assign('offer', $offer);
    }

    /**
     * action new
     *
     * @return string|object|null|void
     */
    public function newAction()
    {
    }

    /**
     * action create
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $newOffer
     * @return string|object|null|void
     */
    public function createAction(\GSC\Tanzpartnersuche\Domain\Model\Offer $newOffer)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->offerRepository->add($newOffer);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $offer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("offer")
     * @return string|object|null|void
     */
    public function editAction(\GSC\Tanzpartnersuche\Domain\Model\Offer $offer)
    {
        $this->view->assign('offer', $offer);
    }

    /**
     * action update
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $offer
     * @return string|object|null|void
     */
    public function updateAction(\GSC\Tanzpartnersuche\Domain\Model\Offer $offer)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->offerRepository->update($offer);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Offer $offer
     * @return string|object|null|void
     */
    public function deleteAction(\GSC\Tanzpartnersuche\Domain\Model\Offer $offer)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->offerRepository->remove($offer);
        $this->redirect('list');
    }

    /**
     * @param \GSC\Tanzpartnersuche\Domain\Repository\OfferRepository $offerRepository
     */
    public function injectOfferRepository(\GSC\Tanzpartnersuche\Domain\Repository\OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
}
