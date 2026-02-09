<?php

namespace BasicDashboard\Web\Categories\Services;

use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Foundations\Shared\BaseCrudService;

class CategoryService extends BaseCrudService
{
    protected bool $useDecoder = false;

    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}
