<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    /**
     * @var DirectorRepository
     */
    private DirectorRepository $directorRepository;
    /**
     * @var TMDBRepository
     */
    private TMDBRepository $TMDBRepository;
    /**
     * @var GenreRepository
     */
    private GenreRepository $genreRepository;


    /**
     * @param ManagerRegistry $registry
     * @param DirectorRepository $directorRepository
     * @param TMDBRepository $TMDBRepository
     * @param GenreRepository $genreRepository
     */
    public function __construct(
        ManagerRegistry $registry,
        DirectorRepository $directorRepository,
        TMDBRepository $TMDBRepository,
        GenreRepository $genreRepository
    ) {
        parent::__construct($registry, Movie::class);

        $this->directorRepository = $directorRepository;
        $this->TMDBRepository = $TMDBRepository;
        $this->genreRepository = $genreRepository;
    }

    /**
     * @param $movieArray
     * @throws ConnectionException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
     */
    public function save($movieArray)
    {
        $this->_em->getConnection()->beginTransaction();

        try {
            $movie = $this->findByTMDBId($movieArray['id']);

            if (!$movie) {
                $movie = new Movie();

                $movie->setTitle($movieArray['title'] ?? '');
                $movie->setOverview($movieArray['overview'] ?? '');
                $movie->setPosterUrl($movieArray['poster_path'] ?? '');
                $movie->setReleaseDate($movieArray['release_date'] ? new \DateTime($movieArray['release_date']) : new \DateTime());

                $TMDB = $this->TMDBRepository->save($movieArray);
                $genres = $this->genreRepository->save($movieArray);

                if(!empty($genres)) {
                    foreach ($genres as $genre) {
                        $movie->addGenre($genre);
                    }
                }

                $movie->setTMDB($TMDB);

                $this->_em->persist($movie);
                $this->_em->flush();

                $this->_em->getConnection()->commit();
            }
        } catch (Exception $e) {
            $this->_em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $id
     * @return float|int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findByTMDBId($id)
    {
        return $this->createQueryBuilder('m')
            ->join('m.TMDB', 't')
            ->andWhere('t.uniqueId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Movie $movie
     * @return Movie[]
     */
    public function normalize(Movie $movie): array
    {
        return [
            'title' => $movie->getTitle(),
            'releaseDate' => $movie->getReleaseDate()->format('Y-m-d'),
            'overview' => $movie->getOverview(),
            'posterUrl' => $movie->getPosterUrl(),
            'createdAt' => $movie->getCreatedAt()->format('Y-m-d H:i:s'),
            'relations' => [
                'tmdb' => $this->TMDBRepository->normalize($movie->getTMDB()),
                'genres' => $this->genreRepository->normalize($movie->getGenre()),
            ]
        ];
    }
}
