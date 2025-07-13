<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'education';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'candidate_id',
        'degree',
        'institution',
        'field_of_study',
        'start_year',
        'end_year',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];

    /**
     * Get the candidate that owns the education.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
