<?php

namespace Si6\Base\Http\Controllers;

use Si6\Base\Http\Requests\CategoryImportRequest;
use Si6\Base\Repositories\CategoryRepository;
use Throwable;

class CategoryController extends BaseController
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryImportRequest $request
     * @throws Throwable
     */
    public function import(CategoryImportRequest $request)
    {
        $this->categoryRepository->import($request->dto());
    }
}
