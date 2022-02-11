<?php

namespace App\Repository;

use App\Entity\TMDB;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method TMDB|null find($id, $lockMode = null, $lockVersion = null)
 * @method TMDB|null findOneBy(array $criteria, array $orderBy = null)
 * @method TMDB[]    findAll()
 * @method TMDB[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TMDBRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TMDB::class);
    }

    /**
     * @param $movieArray
     * @return TMDB
     * @throws ConnectionException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function save($movieArray): TMDB
    {
        $this->_em->getConnection()->beginTransaction();

        try {
            $TMDB = new TMDB();

            $TMDB->setUniqueId($movieArray['id'] ?? '');
            $TMDB->setVoteAverage($movieArray['vote_average'] ?? '');
            $TMDB->setVoteCount($movieArray['vote_count'] ?? '');

            $this->_em->persist($TMDB);
            $this->_em->flush();

            $this->_em->getConnection()->commit();

            return $TMDB;
        } catch (Exception $e) {
            $this->_em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param TMDB $TMDB
     * @return array
     */
    public function normalize(TMDB $TMDB): array
    {
        return [
            'uniqueId' => $TMDB->getUniqueId(),
            'voteAverage' => $TMDB->getVoteAverage(),
            'voteCount' => $TMDB->getVoteCount(),
            'createdAt' => $TMDB->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
