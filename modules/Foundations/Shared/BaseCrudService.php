<?php

namespace BasicDashboard\Foundations\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

abstract class BaseCrudService
{
    protected Model $model;
    protected bool $useDecoder = true;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function paginate(array $request): LengthAwarePaginator
    {
        return $this->model
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function findOrFail(string $id): Model
    {
        $resolvedId = $this->useDecoder ? customDecoder($id) : $id;
        return $this->model->findOrFail($resolvedId);
    }

    public function store(array $request): Model
    {
        return DB::transaction(function () use ($request) {
            $payload = array_merge($request, ['created_by' => Auth::id()]);
            return $this->model->create($payload);
        });
    }

    public function update(array $request, string $id): Model
    {
        return DB::transaction(function () use ($request, $id) {
            $model = $this->findOrFail($id);
            $payload = array_merge($request, ['updated_by' => Auth::id()]);
            $model->update($payload);
            return $model;
        });
    }

    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            $model = $this->findOrFail($id);
            $model->update(['deleted_by' => Auth::id()]);
            $model->delete();
        });
    }
}
