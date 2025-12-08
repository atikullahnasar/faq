<?php

namespace atikullahnasar\faq\Services\Faqs;

interface FaqServiceInterface
{
    public function getAll();
    public function paginate();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
}
