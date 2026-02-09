<?php

namespace BasicDashboard\Web\Categories\Services;

use BasicDashboard\Foundations\Domain\Categories\Category;
use BasicDashboard\Foundations\Shared\BaseCrudService;

class CategoryService extends BaseCrudService
{
    // useDecoder defaults to true in BaseCrudService

    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}
