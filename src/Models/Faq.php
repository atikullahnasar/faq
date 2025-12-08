<?php
namespace atikullahnasar\faq\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $table = 'beft_faqs';

    protected $fillable = [
        'question',
        'answer',
        'status',
        'faqorder',
    ];

    public function toggleStatus(): bool
    {
        $this->status = $this->status === 'Active' ? 'Inactive' : 'Active';
        return $this->save();
    }
}
