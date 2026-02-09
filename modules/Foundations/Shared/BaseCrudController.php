<?php

namespace BasicDashboard\Foundations\Shared;

use BasicDashboard\Web\Common\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\View\View;
use Throwable;

abstract class BaseCrudController extends BaseController
{
    protected $service;
    protected string $viewPath;
    protected string $routePrefix;
    protected string $langPath;
    protected string $resourceClass;
    protected ?string $editResourceClass = null;

    public function __construct(
        protected ResponseFactory $responseFactory
    ) {
    }

    public function index(Request $request): View
    {
        $data = $this->service->paginate($request->all());
        $data = ($this->resourceClass)::collection($data)->response()->getData(true);
        return $this->responseFactory->successView($this->viewPath . '.index', $data);
    }

    public function create(): View
    {
        return view($this->viewPath . '.create');
    }

    protected function performStore(Request $request): mixed
    {
        try {
            $model = $this->service->store($request->all());
            $message = __($this->langPath . '_created');

            if ($request->ajax()) {
                return $this->responseFactory->successAjaxResponse($message, $model);
            }

            return $this->responseFactory->successIndexRedirect($this->routePrefix, $message);
        } catch (Throwable $e) {
            $this->LogError(get_class($this) . " store failed", $e);

            if ($request->ajax()) {
                return $this->responseFactory->failAjaxResponse($e->getMessage());
            }

            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function edit(string $id): View | RedirectResponse
    {
        try {
            $model = $this->service->findOrFail($id);
            $resourceClass = $this->editResourceClass ?? $this->resourceClass;
            $data = new $resourceClass($model);
            $responseData = $data->response()->getData(true);
            $transformedData = $responseData['data'] ?? $responseData;
            return $this->responseFactory->successView($this->viewPath . ".edit", $transformedData);
        } catch (Throwable $e) {
            $this->LogError(get_class($this) . " edit failed", $e);
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function show(string $id): View | RedirectResponse
    {
        try {
            $model = $this->service->findOrFail($id);
            $data = new $this->resourceClass($model);
            $responseData = $data->response()->getData(true);
            $transformedData = $responseData['data'] ?? $responseData;
            return $this->responseFactory->successView($this->viewPath . '.show', $transformedData);
        } catch (Throwable $e) {
            $this->LogError(get_class($this) . " show failed", $e);
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    protected function performUpdate(Request $request, string $id): mixed
    {
        try {
            $model = $this->service->update($request->all(), $id);
            $message = __($this->langPath . '_updated');

            if ($request->ajax()) {
                return $this->responseFactory->successAjaxResponse($message, $model);
            }

            return $this->responseFactory->successShowRedirect($this->routePrefix, $id, $message);
        } catch (Throwable $e) {
            $this->LogError(get_class($this) . " update failed", $e);

            if ($request->ajax()) {
                return $this->responseFactory->failAjaxResponse($e->getMessage());
            }

            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    protected function performDestroy(Request $request): mixed
    {
        try {
            $id = method_exists($request, 'validated') ? ($request->validated()['id'] ?? $request->get('id')) : $request->get('id');
            $this->service->delete($id);
            $message = __($this->langPath . '_deleted');

            if ($request->ajax()) {
                return $this->responseFactory->successAjaxResponse($message, null);
            }

            return $this->responseFactory->successIndexRedirect($this->routePrefix, $message);
        } catch (Throwable $e) {
            $this->LogError(get_class($this) . " destroy failed", $e);

            if ($request->ajax()) {
                return $this->responseFactory->failAjaxResponse($e->getMessage());
            }

            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }
}
