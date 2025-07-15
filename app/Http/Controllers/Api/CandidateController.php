<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CandidateShowRequest;
use App\Http\Resources\CandidateCollection;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use App\Models\Constituency;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Http\Requests\Api\CandidateStoreRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    public function show(Request $request)
    {
        try {
            if (!$request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Candidate ID is required',
                ], 400);
            }
    
            if (!Candidate::where('id', $request->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Candidate not found',
                ], 404);
            }
    
            $candidate = Candidate::findOrFail($request->id);
            $candidate->load('user', 'constituency', 'education');
            
            // Transform image URLs
            $candidate = $this->transformCandidateImages($candidate);
            
            return response()->json([
                'success' => true,
                'data' => $candidate,
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching candidate',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            // Start with base query
            $query = Candidate::with('user', 'constituency', 'education');

            // Only show active candidates
            $query->whereHas('user', function ($q) {
                $q->where('is_active', true);
            });

            // Search functionality
            if ($request->filled('search') || $request->filled('q')) {
                $searchTerm = $request->get('search') ?: $request->get('q');

                $query->where(function ($q) use ($searchTerm) {
                    // Search in user names
                    $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%")
                            ->orWhere('email', 'like', "%{$searchTerm}%");
                    })
                        // Search in candidate fields
                        ->orWhere('party_bloc_name', 'like', "%{$searchTerm}%")
                        ->orWhere('phone', 'like', "%{$searchTerm}%")
                        ->orWhere('biography', 'like', "%{$searchTerm}%")
                        ->orWhere('current_position', 'like', "%{$searchTerm}%")
                        ->orWhere('campaign_slogan', 'like', "%{$searchTerm}%")
                        ->orWhere('achievements', 'like', "%{$searchTerm}%")
                        ->orWhere('experience', 'like', "%{$searchTerm}%")
                        ->orWhere('skills', 'like', "%{$searchTerm}%")
                        // Search in constituency
                        ->orWhereHas('constituency', function ($constQuery) use ($searchTerm) {
                            $constQuery->where('name', 'like', "%{$searchTerm}%");
                        });
                });
            }

            // Filter by constituency
            if ($request->filled('constituency_id')) {
                $query->where('constituency_id', $request->constituency_id);
            }

            // Filter by party/bloc
            if ($request->filled('party_bloc_name')) {
                $query->where('party_bloc_name', 'like', "%{$request->party_bloc_name}%");
            }

            // Filter by list number
            if ($request->filled('list_number')) {
                $query->where('list_number', $request->list_number);
            }

            // Advanced filters
            if ($request->filled('has_education')) {
                $hasEducation = filter_var($request->has_education, FILTER_VALIDATE_BOOLEAN);
                if ($hasEducation) {
                    $query->has('education');
                } else {
                    $query->doesntHave('education');
                }
            }

            if ($request->filled('has_social_links')) {
                $hasSocialLinks = filter_var($request->has_social_links, FILTER_VALIDATE_BOOLEAN);
                if ($hasSocialLinks) {
                    $query->where(function ($q) {
                        $q->whereNotNull('facebook_link')
                            ->orWhereNotNull('linkedin_link')
                            ->orWhereNotNull('instagram_link')
                            ->orWhereNotNull('twitter_link')
                            ->orWhereNotNull('youtube_link')
                            ->orWhereNotNull('tiktok_link')
                            ->orWhereNotNull('website_link');
                    });
                }
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            // Validate sort fields
            $allowedSortFields = [
                'created_at',
                'updated_at',
                'party_bloc_name',
                'list_number',
                'phone',
                'constituency_id'
            ];

            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            } else {
                // Default sorting
                $query->orderBy('created_at', 'desc');
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            // Validate per_page
            if (!is_numeric($perPage) || $perPage < 1 || $perPage > 100) {
                $perPage = 15;
            }

            // Get results
            // Get results
            if ($request->get('paginate', true) && $perPage !== 'all') {
                $candidates = $query->paginate($perPage, ['*'], 'page', $page);

                // Transform image URLs
                $transformedCandidates = $this->transformCandidatesCollection($candidates->items());

                $response = [
                    'success' => true,
                    'data' => $transformedCandidates,
                    'meta' => [
                        'pagination' => [
                            'total' => $candidates->total(),
                            'count' => $candidates->count(),
                            'per_page' => $candidates->perPage(),
                            'current_page' => $candidates->currentPage(),
                            'last_page' => $candidates->lastPage(),
                            'from' => $candidates->firstItem(),
                            'to' => $candidates->lastItem(),
                        ],
                        'links' => [
                            'first' => $candidates->url(1),
                            'last' => $candidates->url($candidates->lastPage()),
                            'prev' => $candidates->previousPageUrl(),
                            'next' => $candidates->nextPageUrl(),
                        ]
                    ],
                    'filters_applied' => $this->getAppliedFilters($request),
                ];
            } else {
                // Return all results without pagination
                $candidates = $query->get();

                // Transform image URLs
                $transformedCandidates = $this->transformCandidatesCollection($candidates);

                $response = [
                    'success' => true,
                    'data' => $transformedCandidates,
                    'meta' => [
                        'total' => $candidates->count(),
                        'count' => $candidates->count(),
                    ],
                    'filters_applied' => $this->getAppliedFilters($request),
                ];
            }

            return response()->json($response);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching candidates',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Get applied filters for debugging/transparency
     */
    private function getAppliedFilters(Request $request): array
    {
        $filters = [];

        if ($request->filled('search') || $request->filled('q')) {
            $filters['search'] = $request->get('search') ?: $request->get('q');
        }

        if ($request->filled('constituency_id')) {
            $filters['constituency_id'] = $request->constituency_id;
        }

        if ($request->filled('party_bloc_name')) {
            $filters['party_bloc_name'] = $request->party_bloc_name;
        }

        if ($request->filled('list_number')) {
            $filters['list_number'] = $request->list_number;
        }

        if ($request->filled('has_education')) {
            $filters['has_education'] = $request->has_education;
        }

        if ($request->filled('has_social_links')) {
            $filters['has_social_links'] = $request->has_social_links;
        }

        if ($request->filled('sort_by')) {
            $filters['sort_by'] = $request->sort_by;
            $filters['sort_order'] = $request->get('sort_order', 'desc');
        }

        return $filters;
    }


    public function store(CandidateStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? '12345678'),
                'role' => 'candidate',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Prepare candidate data
            $candidateData = $request->only([
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
                'facebook_link',
                'linkedin_link',
                'instagram_link',
                'twitter_link',
                'youtube_link',
                'tiktok_link',
                'website_link'
            ]);

            $candidateData['user_id'] = $user->id;

            // Handle file uploads
            if ($request->hasFile('profile_image')) {
                $candidateData['profile_image'] = $request->file('profile_image')
                    ->store('candidates/profile-images', 'public');
            }

            if ($request->hasFile('profile_banner_image')) {
                $candidateData['profile_banner_image'] = $request->file('profile_banner_image')
                    ->store('candidates/banner-images', 'public');
            }

            // Create candidate
            $candidate = Candidate::create($candidateData);

            // Create education records
            if ($request->has('education')) {
                foreach ($request->education as $education) {
                    $candidate->education()->create($education);
                }
            }

            DB::commit();

            // Load relationships
            $candidate->load('user', 'constituency', 'education');

            return response()->json([
                'success' => true,
                'message' => 'Candidate created successfully',
                'data' => $candidate,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            // Clean up uploaded files on error
            if (isset($candidateData['profile_image'])) {
                Storage::disk('public')->delete($candidateData['profile_image']);
            }
            if (isset($candidateData['profile_banner_image'])) {
                Storage::disk('public')->delete($candidateData['profile_banner_image']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error creating candidate',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function Constituencies()
    {
        $constituencies = Constituency::all();
        return response()->json([
            'success' => true,
            'data' => $constituencies,
        ]);
    }

    /**
     * Transform candidate data to include full image URLs
     */
    private function transformCandidateImages($candidate)
    {
        if (is_array($candidate) || is_object($candidate)) {
            // Handle single candidate
            if (isset($candidate->profile_image) && $candidate->profile_image) {
                $candidate->profile_image = asset('storage/' . $candidate->profile_image);
            }

            if (isset($candidate->profile_banner_image) && $candidate->profile_banner_image) {
                $candidate->profile_banner_image = asset('storage/' . $candidate->profile_banner_image);
            }

            return $candidate;
        }

        return $candidate;
    }

    /**
     * Transform collection of candidates
     */
    private function transformCandidatesCollection($candidates)
    {
        if (is_array($candidates)) {
            return array_map([$this, 'transformCandidateImages'], $candidates);
        }

        // Handle Laravel collection
        return $candidates->map(function ($candidate) {
            return $this->transformCandidateImages($candidate);
        });
    }
}