<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    /**
     * @throws ConnectionException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function save($movieArray): array
    {
        $genres = [];
        $this->_em->getConnection()->beginTransaction();

        try {
            foreach ($movieArray['genre_ids'] as $genreId) {
                $genre = new Genre();

                $genre->setUniqueId($genreId);
                $this->_em->persist($genre);

                $genres[] = $genre;
            }

            $this->_em->flush();
            $this->_em->getConnection()->commit();

            return $genres;
        } catch (Exception $e) {
            $this->_em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Collection $genres
     * @return array
     */
    public function normalize(Collection $genres): array
    {
        $genresArr = [];

        foreach ($genres as $genre) {
            $genresArr[] = [
                'uniqueId' => $genre->getUniqueId()
            ];
        }

        return $genresArr;
    }
}
