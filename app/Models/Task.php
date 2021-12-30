<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    public const NOT_STARTED = 1;
    public const STARTED = 2;
    public const PENDING = 3;

    protected $fillable = [
        'title',
        'todo_list_id',
        'status',
        'description',
    ];

    public function todo_list(): BelongsTo
    {
    	return $this->belongsTo(TodoList::class);
    }
}
