<?php

namespace BasicDashboard\Web\Marks\Services;

use BasicDashboard\Foundations\Domain\Marks\Mark;
use Illuminate\Support\Facades\Auth;

class MarkService
{
    public function __construct(
        private Mark $mark
    ) {}

    public function paginate(array $request)
    {
        return $this->mark
            ->with(['student', 'exam', 'subject'])
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Mark
    {
        $request['created_by'] = Auth::id();
        return $this->mark->create($request);
    }

    public function update(array $request, string $id): Mark
    {
        $decodedId = customDecoder($id);
        $mark = $this->mark->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $mark->update($request);
        return $mark;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $mark = $this->mark->findOrFail($decodedId);
        $mark->delete();
    }
}
