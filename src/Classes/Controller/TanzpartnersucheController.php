<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;


/**
 * This file is part of the "tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Martin Arend <tanzpartnersuche@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * TanzpartnersucheController
 */
class TanzpartnersucheController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * tanzpartnersucheRepository
     *
     * @var \GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository
     */
    protected $tanzpartnersucheRepository = null;

    /**
     * @param \GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository $tanzpartnersucheRepository
     */
    public function injectTanzpartnersucheRepository(\GSC\Tanzpartnersuche\Domain\Repository\TanzpartnersucheRepository $tanzpartnersucheRepository)
    {
        $this->tanzpartnersucheRepository = $tanzpartnersucheRepository;
    }

    /**
     * action index
     *
     * @return string|object|null|void
     */
    public function indexAction()
    {
    }

    /**
     * action show
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function showAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->view->assign('tanzpartnersuche', $tanzpartnersuche);
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
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche
     * @return string|object|null|void
     */
    public function createAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $newTanzpartnersuche)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->add($newTanzpartnersuche);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("tanzpartnersuche")
     * @return string|object|null|void
     */
    public function editAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->view->assign('tanzpartnersuche', $tanzpartnersuche);
    }

    /**
     * action update
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function updateAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->update($tanzpartnersuche);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche
     * @return string|object|null|void
     */
    public function deleteAction(\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche $tanzpartnersuche)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->tanzpartnersucheRepository->remove($tanzpartnersuche);
        $this->redirect('list');
    }

    /**
     * action verify
     *
     * @return string|object|null|void
     */
    public function verifyAction()
    {
    }

    /**
     * action search
     *
     * @return string|object|null|void
     */
    public function searchAction()
    {
    }

    /**
     * action help
     *
     * @return string|object|null|void
     */
    public function helpAction()
    {
    }

    /**
     * action detail
     *
     * @return string|object|null|void
     */
    public function detailAction()
    {
    }

    /**
     * action login
     *
     * @return string|object|null|void
     */
    public function loginAction()
    {
    }
}
