<?php declare(strict_types = 1);

namespace Joking\Controller;

use Joking\Application\CategoryListService;
use Joking\Application\CategorySearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController
{
    private CategorySearchService $categorySearchService;
    private CategoryListService $categoryListService;

    public function __construct(
        CategorySearchService $categorySearchService,
        CategoryListService $categoryListService
    ) {
        $this->categorySearchService = $categorySearchService;
        $this->categoryListService = $categoryListService;
    }

    /**
     * @Route("/category", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function list()
    {
        // For simplicity all the status code responses will be 200. Ideally
        // we would want to adapt them for our API contract.
        return new JsonResponse($this->categoryListService->process(), JsonResponse::HTTP_OK);
    }

    /**
     * For simplicity parameter validation has been delegated to Route.
     * It will cause a non route found if no match.
     * @Route("/category/{category<\w+>}", methods={"GET"})
     *
     * @param string $category
     *
     * @return JsonResponse
     */
    public function getByCategory(string $category)
    {
        try {
            return new JsonResponse($this->categorySearchService->process($category), JsonResponse::HTTP_OK);
        } catch (\Throwable $t) {
            // For simplicity, general error. Ideally we should record the issue
            // and send back to the client a non internal message.
            return new JsonResponse($t->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
