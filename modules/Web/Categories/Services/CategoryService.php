<?php

namespace BasicDashboard\Web\Categories\Services;

use BasicDashboard\Foundations\Domain\Categories\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService 
{
    public function __construct(
        private Category $category,
    )
    {
    }

    public function paginate(array $request) :LengthAwarePaginator
    {
        $categoryList = $this->category
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
        return $categoryList;
    }

    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $this->category->create([
                ...$request,
                'created_by' => auth()->id(),
            ]);
        });
    }

    public function findOrFail(string $id): Category
    {
        $category = $this->category->findOrFail($id);
        return $category;
    }


    public function update(array $request, string $id): void
    {
        DB::transaction(function () use ($request , $id) {
            $category = $this->category->findOrFail($id);
            $category->update([
                ...$request,
                'updated_by' => auth()->id(),
            ]);
        });
    }

    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            $category = $this->category->findOrFail($id);
            $category->update([
                'deleted_by' => auth()->id(),
            ]);
            $category->delete();
        });
    }

}
