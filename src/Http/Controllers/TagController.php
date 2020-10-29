<?php

namespace Si6\Base\Http\Controllers;

use Si6\Base\Http\Requests\CategoryImportRequest;
use Si6\Base\Http\Requests\TagImportRequest;
use Si6\Base\Repositories\CategoryRepository;
use Si6\Base\Repositories\TagRepository;
use Throwable;

class TagController extends BaseController
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TagController constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param TagImportRequest $request
     */
    public function import(TagImportRequest $request)
    {
        $this->tagRepository->import($request->dto());
    }
}
