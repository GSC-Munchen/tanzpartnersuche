<?php

declare(strict_types=1);

namespace GSC\Tanzpartnersuche\Domain\Repository;
use TYPO3\CMS\Extbase\Persistence\Repository;


/**
 * This file is part of the "tanzpartnersuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Martin Arend <tanzpartnersuche@gsc-muenchen.de>, GSC München e.V.
 */

/**
 * The repository for Tanzpartnersuches
 */
class TanzpartnersucheRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

/**
     * 
     * @param string $checkUsername
     * @return QueryResultInterface|array
     * @api
     */
    public function findUserByUsername($checkUsername) 
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('username',$checkUsername),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute();

        // Return NULL, if there is no match
        if (count($result)=='0') 
            $result = NULL;

        return $result;
    }

    /**
     * 
     * @param string $checkmail
     * @return QueryResultInterface|array
     * @api
     */
    public function findUserByEmail($checkmail) 
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('email',$checkmail),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute();

        // Return NULL, if there is no match
        if (count($result)=='0') 
            $result = NULL;

        return $result;
    }

    /**
     * 
     * @param string $checkVerification
     * @param string $checkUsername
     * @return QueryResultInterface|array
     * @api
     */
    public function findUserByValidation($checkUsername, $checkVerification) 
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('username',$checkUsername),
                $query->like('verificationcode',$checkVerification),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute();

        // Return NULL, if there is no match
        if (count($result)=='0') 
            $result = NULL;

        return $result;
    }

    /**
     * 
     * @param string $checkVerification
     * @param string $checkUsername
     * @return QueryResultInterface|array
     * @api
     */
    public function UserValidationCheck($checkUsername, $checkVerification) 
    {
        // Query aufbauen
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('username',$checkUsername),
                $query->like('verificationcode',$checkVerification),
                $query->like('hidden','0'),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute();

        // Return NULL, if there is no match
        if (count($result)=='0') 
            $result = NULL;

        return $result;
    }
}
