<?php

namespace App\Controller;

use App\Service\MovieApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    private MovieApiService $movieApiService;

    public function __construct(MovieApiService $movieApiService)
    {
        $this->movieApiService = $movieApiService;
    }

    /**
     * @Route("/list", name="list")
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        try {
            $page = $request->get('page') ?? 1;
            $response = $this->movieApiService->getList($page);

            return new JsonResponse($response);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @Route("/saved-list", name="saved_list")
     * @return Response
     */
    public function savedList(Request $request): Response
    {
        try {
            $page = $request->get('page') ?? 1;
            $response = $this->movieApiService->getSavedList($page);

            return new JsonResponse($response);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @Route("/save-list", name="save_list")
     * @param Request $request
     * @return Response
     */
    public function save(Request $request): Response
    {
        try {
            $page = $request->get('page') ?? 1;
            $response = $this->movieApiService->saveList($page);

            return new JsonResponse($response);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @Route("/{id}", name="get_movie")
     * @param int $id
     * @return Response
     */
    public function getMovie(int $id): Response
    {
        try {
            $response = $this->movieApiService->getMovie($id);

            return new JsonResponse([
                'result' => $response
            ]);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), null, $e->getCode());
        }
    }
}
