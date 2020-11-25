<?php declare(strict_types = 1);

namespace Joking\Controller;

use Joking\Application\WordSearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WordController
{
    private WordSearchService $wordSearchService;

    public function __construct(WordSearchService $wordSearchService)
    {
        $this->wordSearchService = $wordSearchService;
    }

    /**
     * For simplicity parameter validation has been delegated to Route.
     * It will cause a non route found if no match.
     * @Route("/word/{word<\w+>}", methods={"GET"})
     *
     * @param string $word
     *
     * @return JsonResponse
     */
    public function getByWord(string $word)
    {
        try {
            return new JsonResponse($this->wordSearchService->process($word), JsonResponse::HTTP_OK);
        } catch (\Throwable $t) {
            // For simplicity, general error. Ideally we should record the issue
            // and send back to the client a non internal message.
            return new JsonResponse($t->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
