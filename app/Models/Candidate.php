<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'constituency_id',
        'party_bloc_name',
        'phone',
        'biography',
        'list_number',
        'current_position',
        'achievements',
        'additional_info',
        'experience',
        'skills',
        'campaign_slogan',
        'voter_promises',
        'profile_image',
        'profile_banner_image',
        'facebook_link',
        'linkedin_link',
        'instagram_link',
        'twitter_link',
        'youtube_link',
        'tiktok_link',
        'website_link',
    ];

    /**
     * Get the user that owns the candidate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the constituency that the candidate belongs to.
     */
    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    /**
     * Get the education records for the candidate.
     */
    public function education()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Get the candidate's full name from user.
     */
    public function getFullNameAttribute()
    {
        return $this->user->full_name ?? '';
    }

    /**
     * Get the profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ? asset('storage/' . $this->profile_image) : null;
    }

    /**
     * Get the profile banner image URL.
     */
    public function getProfileBannerImageUrlAttribute()
    {
        return $this->profile_banner_image ? asset('storage/' . $this->profile_banner_image) : null;
    }
}