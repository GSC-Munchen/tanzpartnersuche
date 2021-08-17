<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Controller;


/**
 * This file is part of the "Tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Peter von Niebelschütz <ias@gsc-muenchen.de>
 *          Martin Arend <ias@gsc-muenchen.de>
 */

/**
 * UserController
 */
class UserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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
        $users = $this->userRepository->findAll();
        $this->view->assign('users', $users);
    }

    /**
     * action show
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function showAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
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
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $newUser
     * @return string|object|null|void
     */
    public function createAction(\GSC\Tanzpartnersuche\Domain\Model\User $newUser)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->add($newUser);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("user")
     * @return string|object|null|void
     */
    public function editAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function updateAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->update($user);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function deleteAction(\GSC\Tanzpartnersuche\Domain\Model\User $user)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->remove($user);
        $this->redirect('list');
    }
}
