<?php

namespace atikullahnasar\faq\Repositories\Faqs;

use atikullahnasar\faq\Models\Faq;
use atikullahnasar\faq\Repositories\BaseRepository;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }
}
