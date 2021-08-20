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
 * MainController
 */
class MainController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * mainRepository
     *
     * @var \GSC\Tanzpartnersuche\Domain\Repository\MainRepository
     */
    protected $mainRepository = null;

    /**
     * @param \GSC\Tanzpartnersuche\Domain\Repository\MainRepository $mainRepository
     */
    public function injectMainRepository(\GSC\Tanzpartnersuche\Domain\Repository\MainRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
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
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $mains = $this->mainRepository->findAll();
        $this->view->assign('mains', $mains);
    }

    /**
     * action show
     *
     * @param \GSC\Tanzpartnersuche\Domain\Model\Main $main
     * @return string|object|null|void
     */
    public function showAction(\GSC\Tanzpartnersuche\Domain\Model\Main $main)
    {
        $this->view->assign('main', $main);
    }

    
    /**
     * action docs
     *
     * @return void
     */
    public function docsAction()
    {
    }

}
