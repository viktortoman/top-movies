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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MovieApiService
{
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

    public function getSavedList(): array
    {
        $movies = $this->em->getRepository(Movie::class)->findAll();
        $list = [];

        foreach ($movies as $movie) {
            $list[] = $this->movieRepository->normalize($movie);
        }

        return $list;
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
}