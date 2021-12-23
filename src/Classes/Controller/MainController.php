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
     * action help
     *
     * @return string|object|null|void
     */
    public function helpAction()
    {
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
     * action search
     *
     * @return string|object|null|void
     */
    public function searchAction()
    {
    }
}
