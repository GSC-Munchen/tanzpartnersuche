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
 * (c) 2022 Martin Arend <tanzpartner@gsc-muenchen.de>, GSC München e.V.
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
     * @param string $checkPassword
     * @param string $checkUsername
     * @return QueryResultInterface|array
     * @api
     */
    public function verifyUserCredentials($checkUsername, $checkPassword) 
    {
        // create Query to read out password hash for corresponding $checkUsername
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('username',$checkUsername),
                $query->like('hidden','0'),
                $query->like('deleted','0')
                )
            );

        $result = $query->execute()->getFirst();

        // Return NULL, if there is no match
        if ($result == NULL)
            return NULL;
        
        // verify password from user submitted input and hash from database
        if (password_verify($checkPassword, $result->getPassword())) {
            return $result; // succesful match of credentials
        } else {
            return NULL;    // no match of credentials
        }
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

    /**
     *
     * @param string $authCode
     * @param string $authUsername
     * @return \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche|null
     */
    public function findTanzpartnerByValidation($authCode, $authUsername): ?\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche
    {
        // create Query
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);

        return $query->matching(
            $query->logicalAnd(
                $query->like('verificationcode',$authCode),
                $query->like('username',$authUsername),
                $query->like('deleted','0')
            )
        )->execute()->getFirst();
    }

    /**
     *
     * @param string $username
     * @return \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche|null
     */
    public function findTanzpartnerByUsername($username): ?\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche
    {
        // create Query
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);

        return $query->matching(
            $query->logicalAnd(
                $query->like('username',$username),
                $query->like('hidden','0'),
                $query->like('deleted','0')
            )
        )->execute()->getFirst();
    }

    /**
     * 
     * @return QueryResultInterface|array
     * @api
     */
    public function findAllActiveProfiles() 
    {
        // set up query
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('hidden','0'),
                $query->like('deleted','0'),
                $query->greaterThanOrEqual('created', strtotime("-6 month"))
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
     * @return QueryResultInterface|array
     * @api
     */
    public function filterProfiles($gender = '', $category = '', $level = '') {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->matching(
            $query->logicalAnd(
                $query->like('hidden','0'),
                $query->like('deleted','0'),
                $query->like('gender', '%'.$gender.'%'),
                $query->like('category', '%'.$category.'%'),
                $query->like('level', '%'.$level.'%')
                )
            );
        return $query->execute();
    }

    /**
     *
     * @param string $authUsername
     * @param string $authEmail
     * @return \GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche|null
     */
    public function UserPasswordReset($authUsername, $authEmail): ?\GSC\Tanzpartnersuche\Domain\Model\Tanzpartnersuche
    {
        // create Query
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);

        return $query->matching(
            $query->logicalAnd(
                $query->like('username',$authUsername),
                $query->like('email',$authEmail),
                $query->like('hidden','0'),
                $query->like('deleted','0')
            )
        )->execute()->getFirst();
    }
}
