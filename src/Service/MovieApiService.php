<?php

namespace App\Service;

use App\Entity\Movie;
use App\Model\ApiResponse;
use App\Repository\MovieRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use GuzzleHttp\Exception\GuzzleException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MovieApiService
{
    private string $pageSize = '20';

    /**
     * @var ApiClientService
     */
    private ApiClientService $apiClientService;

    /**
     * @var MovieRepository
     */
    private MovieRepository $movieRepository;

    private EntityManagerInterface $em;

    /**
     * @param ApiClientService $apiClientService
     * @param MovieRepository $movieRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ApiClientService $apiClientService,
        MovieRepository $movieRepository,
        EntityManagerInterface $em
    ) {
        $this->apiClientService = $apiClientService;
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    /**
     * @param $page
     * @return array
     */
    public function getList($page): array
    {
        try {
            $response = $this->apiClientService->call(
                ApiResponse::METHOD_GET,
                'movie/top_rated', [
                    'page' => $page
                ]
            );

            return $response->message;
        } catch (GuzzleException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }
    }

    #[ArrayShape(['results' => "array", 'totalItems' => "int", 'pages' => "float"])]
    public function getSavedList(int $page = 1): array
    {
        $movies = $this->em->getRepository(Movie::class);
        $query = $movies->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->getQuery();

        // load doctrine Paginator
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItems = count($paginator);

        // get total pages
        $pagesCount = ceil($totalItems / $this->pageSize);

        // now get one page's items:
        $paginator
            ->getQuery()
            ->setFirstResult($this->pageSize * ($page-1)) // set the offset
            ->setMaxResults($this->pageSize); // set the limit

        $list = [];

        foreach ($paginator as $movie) {
            $list[] = $this->movieRepository->normalize($movie);
        }

        return [
            'results' => $list,
            'totalItems' => $totalItems,
            'pages' => $pagesCount
        ];
    }

    /**
     * @param null $page
     * @return array
     * @throws ConnectionException
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveList($page = null): array
    {
        $response = [];

        try {
            $result = $this->getList($page);
            $movies = $result['results'];

            foreach ($movies as $movie) {
                $this->movieRepository->save($movie);
            }

            $response['message'] = $page ?
                'Movie list save successfully on page: ' . $page : 'Movie list save successfully.';
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }

        return $response;
    }

    public function getMovie(int $id): array
    {
        try {
            $response = $this->apiClientService->call(
                ApiResponse::METHOD_GET,
                'movie/' . $id, []
            );

            return $response->message;
        } catch (GuzzleException $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }
    }
}