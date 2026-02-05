<?php

namespace BasicDashboard\Web\Categories\Services;

use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Categories\Resources\CategoryResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Exception;

class CategoryService extends BaseController
{
    const VIEW = 'admin.category';
    const ROUTE = 'categories';
    const LANG_PATH = "category.category";

    public function __construct(
        private Category $category,
        private ResponseFactory $responseFactory
    )
    {
    }

    public function index(array $request): View
    {
        $categoryList = $this->category
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));

        $categoryList = CategoryResource::collection($categoryList)->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW.".index", $categoryList);
    }

    public function create(): View
    {
        return view(self::VIEW.'.create');
    }

    public function store($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            $request['created_by'] = Auth::id();
            $this->category->create($request);
            \DB::commit();
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError(null, $e->getMessage());
        }
    }

    public function edit(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);
        $category = $this->category->findOrFail($id);
        $category = new CategoryResource($category);
        $category = $category->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . ".edit", $category);
    }


    public function show(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);
        $category = $this->category->findOrFail($id);
        $category = new CategoryResource($category);
        $category = $category->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $category);
    }

    public function update($request, string $id): RedirectResponse
    {
         try {
            \DB::beginTransaction();
            $decodedId = customDecoder($id);
            $category = $this->category->findOrFail($decodedId);
            $category->update($request);
            \DB::commit();
            return $this->responseFactory->successShowRedirect(self::ROUTE, $id, __(self::LANG_PATH . '_updated'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function destroy($request): RedirectResponse
    {
         try {
            \DB::beginTransaction();
            $id = customDecoder($request['id']);
            $this->category->destroy($id);
            \DB::commit();
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_deleted'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

}
