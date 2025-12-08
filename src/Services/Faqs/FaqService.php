<?php

namespace atikullahnasar\faq\Services\Faqs;

use atikullahnasar\faq\Repositories\Faqs\FaqRepositoryInterface;

class FaqService implements FaqServiceInterface
{
    protected $faqRepository;

    public function __construct(FaqRepositoryInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function getAll()
    {
        return $this->faqRepository->all();
    }

    public function paginate()
    {
        return $this->faqRepository->paginate(null, 10, ['*'], [], ['status' => 'Active']);
    }

    public function findById(int $id)
    {
        return $this->faqRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->faqRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->faqRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->faqRepository->delete($id);
    }

    public function toggleStatus(int $id)
    {
        $faq = $this->faqRepository->find($id);
        $faq->toggleStatus();
        return $faq;
    }
}
