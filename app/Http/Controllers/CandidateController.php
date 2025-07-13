<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Education;
use App\Models\Constituency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:candidate']);
    }

    public function dashboard()
    {
        $candidate = auth()->user()->candidate;
        $profileCompletion = $this->calculateProfileCompletion($candidate);
        
        return view('dashboard.candidate.dashboard', compact('candidate', 'profileCompletion'));
    }

    public function profile()
    {
        $candidate = auth()->user()->candidate;
        $constituencies = Constituency::all();
        return view('dashboard.candidate.profile', compact('candidate', 'constituencies'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'constituency_id' => ['required', 'exists:constituencies,id'],
            'party_bloc_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'biography' => ['required', 'string'],
            'list_number' => ['nullable', 'string', 'max:50'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'achievements' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'campaign_slogan' => ['nullable', 'string', 'max:255'],
            'voter_promises' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'profile_banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        $candidate = auth()->user()->candidate;
        $updateData = $request->except(['profile_image', 'profile_banner_image']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($candidate->profile_image && Storage::disk('public')->exists($candidate->profile_image)) {
                Storage::disk('public')->delete($candidate->profile_image);
            }
            
            $profileImagePath = $request->file('profile_image')->store('candidates/profile-images', 'public');
            $updateData['profile_image'] = $profileImagePath;
        }

        // Handle profile banner image upload
        if ($request->hasFile('profile_banner_image')) {
            // Delete old image if exists
            if ($candidate->profile_banner_image && Storage::disk('public')->exists($candidate->profile_banner_image)) {
                Storage::disk('public')->delete($candidate->profile_banner_image);
            }
            
            $bannerImagePath = $request->file('profile_banner_image')->store('candidates/banner-images', 'public');
            $updateData['profile_banner_image'] = $bannerImagePath;
        }

        $candidate->update($updateData);
        
        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function removeProfileImage()
    {
        $candidate = auth()->user()->candidate;
        
        if ($candidate->profile_image && Storage::disk('public')->exists($candidate->profile_image)) {
            Storage::disk('public')->delete($candidate->profile_image);
        }
        
        $candidate->update(['profile_image' => null]);
        
        return back()->with('success', 'تم حذف صورة الملف الشخصي بنجاح');
    }

    public function removeProfileBannerImage()
    {
        $candidate = auth()->user()->candidate;
        
        if ($candidate->profile_banner_image && Storage::disk('public')->exists($candidate->profile_banner_image)) {
            Storage::disk('public')->delete($candidate->profile_banner_image);
        }
        
        $candidate->update(['profile_banner_image' => null]);
        
        return back()->with('success', 'تم حذف صورة الغلاف بنجاح');
    }

    public function education()
    {
        $candidate = auth()->user()->candidate;
        $education = $candidate->education;
        
        return view('dashboard.candidate.education', compact('education'));
    }

    public function addEducation(Request $request)
    {
        $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'end_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'description' => ['nullable', 'string'],
        ]);

        $candidate = auth()->user()->candidate;
        
        Education::create([
            'candidate_id' => $candidate->id,
            'degree' => $request->degree,
            'institution' => $request->institution,
            'field_of_study' => $request->field_of_study,
            'start_year' => $request->start_year,
            'end_year' => $request->end_year,
            'description' => $request->description,
        ]);
        
        return back()->with('success', 'تمت إضافة المؤهل العلمي بنجاح');
    }

    public function updateEducation(Request $request, Education $education)
    {
        // Check if the education belongs to the authenticated candidate
        if ($education->candidate_id !== auth()->user()->candidate->id) {
            abort(403, 'غير مخول للوصول');
        }

        $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'end_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'description' => ['nullable', 'string'],
        ]);

        $education->update($request->all());
        
        return back()->with('success', 'تم تحديث المؤهل العلمي بنجاح');
    }

    public function deleteEducation(Education $education)
    {
        // Check if the education belongs to the authenticated candidate
        if ($education->candidate_id !== auth()->user()->candidate->id) {
            abort(403, 'غير مخول للوصول');
        }

        $education->delete();
        
        return back()->with('success', 'تم حذف المؤهل العلمي بنجاح');
    }

    private function calculateProfileCompletion($candidate)
    {
        $fields = [
            'list_number', 'current_position', 
            'achievements', 'additional_info', 'experience', 
            'skills', 'campaign_slogan', 'voter_promises',
            'profile_image', 'profile_banner_image'
        ];
        
        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($candidate->$field)) {
                $completed++;
            }
        }
        
        // Add education to completion calculation
        if ($candidate->education->count() > 0) {
            $completed++;
            $fields[] = 'education';
        }
        
        return round(($completed / count($fields)) * 100);
    }
}