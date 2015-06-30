<?php

namespace Oro\Bundle\ImapBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Oro\Bundle\EmailBundle\Entity\EmailFolder;
use Oro\Bundle\EmailBundle\Entity\EmailOrigin;
use Oro\Bundle\ImapBundle\Entity\ImapEmail;

class ImapEmailRepository extends EntityRepository
{
    /**
     * @param EmailFolder $folder
     * @param int[]       $uids
     *
     * @return QueryBuilder
     */
    public function getEmailsByUidsQueryBuilder(EmailFolder $folder, array $uids)
    {
        return $this->createQueryBuilder('imap_email')
            ->innerJoin('imap_email.email', 'email')
            ->innerJoin('email.emailUsers', 'email_users')
            ->innerJoin('email_users.folder', 'folder')
            ->where('folder = :folder AND imap_email.uid IN (:uids)')
            ->setParameter('folder', $folder)
            ->setParameter('uids', $uids);
    }

    /**
     * @param EmailFolder $folder
     * @param int[]       $uids
     *
     * @return int[] Existing UIDs
     */
    public function getExistingUids(EmailFolder $folder, array $uids)
    {
        $rows = $this->getEmailsByUidsQueryBuilder($folder, $uids)
            ->select('imap_email.uid')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($rows as $row) {
            $result[] = $row['uid'];
        }

        return $result;
    }

    /**
     * @param EmailOrigin $origin
     * @param string[]    $messageIds
     *
     * @return QueryBuilder
     */
    public function getEmailsByMessageIdsQueryBuilder(EmailOrigin $origin, array $messageIds)
    {
        return $this->createQueryBuilder('imap_email')
            ->innerJoin('imap_email.imapFolder', 'imap_folder')
            ->innerJoin('imap_email.email', 'email')
            ->innerJoin('email.emailUsers', 'email_users')
            ->innerJoin('email_users.folder', 'folder')
            ->where('folder.origin = :origin AND email.messageId IN (:messageIds)')
            ->setParameter('origin', $origin)
            ->setParameter('messageIds', $messageIds);
    }

    /**
     * @param EmailOrigin $origin
     * @param string[]    $messageIds
     *
     * @return ImapEmail[] Existing emails
     */
    public function getEmailsByMessageIds(EmailOrigin $origin, array $messageIds)
    {
        $rows = $this->getEmailsByMessageIdsQueryBuilder($origin, $messageIds)
            ->select('imap_email, email, email_users, imap_folder, folder')
            ->getQuery()
            ->getResult();

        return $rows;
    }

    /**
     * @param EmailOrigin $origin
     * @param string[]    $messageIds
     *
     * @return ImapEmail[] Existing emails
     */
    public function getOutdatedEmailsByMessageIds(EmailOrigin $origin, array $messageIds)
    {
        $rows = $this->getEmailsByMessageIdsQueryBuilder($origin, $messageIds)
            ->select('imap_email, email, email_users, imap_folder, folder')
            ->andWhere('folder.outdatedAt IS NOT NULL')
            ->getQuery()
            ->getResult();

        return $rows;
    }

    /**
     * @param             $uids
     * @param EmailFolder $folder
     *
     * @return array
     */
    public function getEmailUserIdsByUIDs($uids, EmailFolder $folder)
    {
        $qb = $this->createQueryBuilder('ie');

        $emailUserIds = $qb->select('email_user.id')
            ->leftJoin('ie.email', 'email')
            ->leftJoin('email.emailUsers', 'email_user')
            ->andWhere('email_user.folder = :folder')
            ->andWhere($qb->expr()->in('ie.uid', ':uids'))
            ->setParameter('uids', $uids)
            ->setParameter('folder', $folder)
            ->getQuery()->getArrayResult();

        $ids = [];
        foreach ($emailUserIds as $emailUserId) {
            $ids[] = $emailUserId['id'];
        }

        return $ids;
    }
}
