<?php

namespace atikullahnasar\faq\Http\Controllers;

use App\Http\Controllers\Controller;
use atikullahnasar\faq\Http\Requests\StoreFaqRequest;
use atikullahnasar\faq\Models\Faq;
use atikullahnasar\faq\Services\Faqs\FaqServiceInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
{
    public function __construct(
        private readonly FaqServiceInterface $faqService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->faqService->getAll();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($item) {
                    return ''; // Actions rendered by JS
                })
                ->editColumn('status', function ($item) {
                    return $item->status;
                })
                ->make(true);
        }
        return view('faq::faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request)
    {
        $this->faqService->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Faq created successfully!'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $faq = $this->faqService->findById($id);
        return $faq;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFaqRequest $request, $faq)
    {
        $this->faqService->update($faq, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Faq updated successfully!'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($faq)
    {
        $this->faqService->delete($faq);
        return response()->json(['success' => true, 'message' => 'Faq deleted successfully!']);
    }

    public function toggleStatus($faq)
    {
        $this->faqService->toggleStatus($faq);
        return response()->json(['success' => true, 'message' => 'Faq status toggled successfully!']);
    }
}
