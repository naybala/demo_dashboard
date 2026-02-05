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

/**
 * CategoryService - Simplified Architecture
 * 
 * This service demonstrates the refactored pattern:
 * - Uses Eloquent models directly (no Repository pattern)
 * - Leverages query scopes defined in Category model
 * - Maintains business logic separation from controllers
 * - Keeps transaction management
 */
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

    /**
     * Display paginated list of categories with optional keyword filtering
     * 
     * Uses Eloquent scopes: filterByKeyword, orderByLatest
     */
    public function index(array $request): View
    {
        // Direct Eloquent query using model scopes (no repository)
        $categoryList = $this->category
            ->filterByKeyword($request['keyword'] ?? null)          // Apply search filter
            ->orderByLatest()                                        // Order by latest activity
            ->paginate($request['paginate'] ?? config('numbers.paginate'));

        $categoryList = CategoryResource::collection($categoryList)->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW.".index", $categoryList);
    }

    public function create(): View
    {
        return view(self::VIEW.'.create');
    }

    /**
     * Create a new category
     * 
     * Uses DB transactions directly instead of repository transaction methods
     */
    public function store($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();  // Direct DB transaction instead of repository method
            
            $request['created_by'] = Auth::id();  // Add created_by manually (was in BaseRepository)
            
            // Direct Eloquent create instead of repository insert
            $this->category->create($request);
            
            \DB::commit();  // Direct DB commit
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (Exception $e) {
            \DB::rollBack();  // Direct DB rollback
            return $this->responseFactory->redirectBackWithError(null, $e->getMessage());
        }
    }

    /**
     * Edit category - fetch category data for editing
     */
    public function edit(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);  // Decode ID (was in BaseRepository)
        $category = $this->category->findOrFail($id);
        $category = new CategoryResource($category);
        $category = $category->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . ".edit", $category);
    }

    /**
     * Show category details
     */
    public function show(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);
        $category = $this->category->findOrFail($id);
        $category = new CategoryResource($category);
        $category = $category->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $category);
    }

    /**
     * Update category
     */
    public function update($request, string $id): RedirectResponse
    {
         try {
            \DB::beginTransaction();
            
            $decodedId = customDecoder($id);
            $category = $this->category->findOrFail($decodedId);
            
            // Direct Eloquent update
            $category->update($request);
            
            \DB::commit();
            return $this->responseFactory->successShowRedirect(self::ROUTE, $id, __(self::LANG_PATH . '_updated'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError(null, $e->getMessage());
        }
    }

    /**
     * Delete category
     */
    public function destroy($request): RedirectResponse
    {
         try {
            \DB::beginTransaction();
            
            $id = customDecoder($request['id']);
            
            // Direct Eloquent delete
            $this->category->destroy($id);
            
            \DB::commit();
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_deleted'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError(null, $e->getMessage());
        }
    }

}
