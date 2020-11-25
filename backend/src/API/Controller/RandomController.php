<?php declare(strict_types = 1);

namespace Joking\Controller;

use Joking\Application\RandomSearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RandomController
{
    private RandomSearchService $randomSearchService;

    public function __construct(RandomSearchService $randomSearchService)
    {
        $this->randomSearchService = $randomSearchService;
    }

    /**
     * @Route("/random", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getRandom()
    {
        try {
            return new JsonResponse($this->randomSearchService->process(), JsonResponse::HTTP_OK);
        } catch (\Throwable $t) {
            // For simplicity, general error. Ideally we should record the issue
            // and send back to the client a non internal message.
            return new JsonResponse($t->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
