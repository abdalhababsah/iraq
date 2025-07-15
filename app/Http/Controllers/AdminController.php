<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Constituency;
use App\Models\Education;
use App\Models\User;
use App\Imports\CandidatesImport;
use App\Exports\CandidatesExport;
use App\Exports\CandidatesTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalCandidates = Candidate::count();
        $activeCandidates = User::where('role', 'candidate')->where('is_active', true)->count();
        $recentCandidates = Candidate::with('user')->latest()->take(5)->get();

        return view('dashboard.admin.dashboard', compact('totalCandidates', 'activeCandidates', 'recentCandidates'));
    }

    public function candidates(Request $request)
    {
        $query = Candidate::with(['user', 'constituency']);

        // Filtering
        if ($request->filled('constituency')) {
            $query->where('constituency_id', $request->constituency);
        }
        if ($request->filled('party_bloc_name')) {
            $query->where('party_bloc_name', 'like', '%' . $request->party_bloc_name . '%');
        }
        if ($request->filled('is_active')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }

        $candidates = $query->paginate(15);
        $constituencies = Constituency::all();

        return view('dashboard.admin.candidates.index', compact('candidates', 'constituencies'));
    }

    public function showCandidate(Candidate $candidate)
    {
        $candidate->load(['user', 'constituency', 'education']);
        $constituencies = Constituency::all();
        return view('dashboard.admin.candidates.show', compact('candidate', 'constituencies'));
    }

    public function editCandidate(Candidate $candidate)
    {
        $candidate->load(['user', 'constituency', 'education']);
        $constituencies = Constituency::all();
        return view('dashboard.admin.candidates.show', compact('candidate', 'constituencies'));
    }

    public function storeCandidate(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'constituency_id' => ['required', 'exists:constituencies,id'],
            'party_bloc_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'biography' => ['required', 'string'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'achievements' => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'campaign_slogan' => ['nullable', 'string', 'max:255'],
            'voter_promises' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'profile_banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'facebook_link' => ['nullable', 'string', 'max:255'],
            'linkedin_link' => ['nullable', 'string', 'max:255'],
            'instagram_link' => ['nullable', 'string', 'max:255'],
            'twitter_link' => ['nullable', 'string', 'max:255'],
            'youtube_link' => ['nullable', 'string', 'max:255'],
            'tiktok_link' => ['nullable', 'string', 'max:255'],
            'website_link' => ['nullable', 'string', 'max:255'],
        ]);

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidate',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $candidateData = [
            'user_id' => $user->id,
            'constituency_id' => $request->constituency_id,
            'party_bloc_name' => $request->party_bloc_name,
            'phone' => $request->phone,
            'biography' => $request->biography,
            'list_number' => $request->list_number,
            'current_position' => $request->current_position,
            'achievements' => $request->achievements,
            'additional_info' => $request->additional_info,
            'experience' => $request->experience,
            'skills' => $request->skills,
            'campaign_slogan' => $request->campaign_slogan,
            'voter_promises' => $request->voter_promises,
            'facebook_link' => $request->facebook_link,
            'linkedin_link' => $request->linkedin_link,
            'instagram_link' => $request->instagram_link,
            'twitter_link' => $request->twitter_link,
            'youtube_link' => $request->youtube_link,
            'tiktok_link' => $request->tiktok_link,
            'website_link' => $request->website_link,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('candidates/profile-images', 'public');
            $candidateData['profile_image'] = $profileImagePath;
        }

        // Handle profile banner image upload
        if ($request->hasFile('profile_banner_image')) {
            $bannerImagePath = $request->file('profile_banner_image')->store('candidates/banner-images', 'public');
            $candidateData['profile_banner_image'] = $bannerImagePath;
        }

        // Create candidate
        $candidate = Candidate::create($candidateData);

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('success', 'تم إنشاء المرشح بنجاح');
    }

    public function updateCandidate(Request $request, Candidate $candidate)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $candidate->user_id],
            'constituency_id' => ['required', 'exists:constituencies,id'],
            'party_bloc_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'biography' => ['required', 'string'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'achievements' => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'campaign_slogan' => ['nullable', 'string', 'max:255'],
            'voter_promises' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'profile_banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'facebook_link' => ['nullable', 'string', 'max:255'],
            'linkedin_link' => ['nullable', 'string', 'max:255'],
            'instagram_link' => ['nullable', 'string', 'max:255'],
            'twitter_link' => ['nullable', 'string', 'max:255'],
            'youtube_link' => ['nullable', 'string', 'max:255'],
            'tiktok_link' => ['nullable', 'string', 'max:255'],
            'website_link' => ['nullable', 'string', 'max:255'],
        ]);

        // Update user
        $candidate->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        $updateData = [
            'constituency_id' => $request->constituency_id,
            'party_bloc_name' => $request->party_bloc_name,
            'phone' => $request->phone,
            'biography' => $request->biography,
            'list_number' => $request->list_number,
            'current_position' => $request->current_position,
            'achievements' => $request->achievements,
            'additional_info' => $request->additional_info,
            'experience' => $request->experience,
            'skills' => $request->skills,
            'campaign_slogan' => $request->campaign_slogan,
            'voter_promises' => $request->voter_promises,
            'facebook_link' => $request->facebook_link,
            'linkedin_link' => $request->linkedin_link,
            'instagram_link' => $request->instagram_link,
            'twitter_link' => $request->twitter_link,
            'youtube_link' => $request->youtube_link,
            'tiktok_link' => $request->tiktok_link,
            'website_link' => $request->website_link,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($candidate->profile_image && Storage::disk('public')->exists($candidate->profile_image)) {
                Storage::disk('public')->delete($candidate->profile_image);
            }

            $profileImagePath = $request->file('profile_image')->store('candidates/profile-images', 'public');
            $updateData['profile_image'] = $profileImagePath;
            
            // Log for debugging
            \Log::info('Profile image uploaded', [
                'path' => $profileImagePath,
                'original_name' => $request->file('profile_image')->getClientOriginalName()
            ]);
        }

        // Handle profile banner image upload
        if ($request->hasFile('profile_banner_image')) {
            // Delete old image if exists
            if ($candidate->profile_banner_image && Storage::disk('public')->exists($candidate->profile_banner_image)) {
                Storage::disk('public')->delete($candidate->profile_banner_image);
            }

            $bannerImagePath = $request->file('profile_banner_image')->store('candidates/banner-images', 'public');
            $updateData['profile_banner_image'] = $bannerImagePath;
            
            // Log for debugging
            \Log::info('Banner image uploaded', [
                'path' => $bannerImagePath,
                'original_name' => $request->file('profile_banner_image')->getClientOriginalName()
            ]);
        }

        // Update candidate
        $candidate->update($updateData);

        // Check if request is AJAX (for image uploads)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('success', 'تم تحديث بيانات المرشح بنجاح');
    }

    public function toggleCandidateStatus(Candidate $candidate)
    {
        $candidate->user->update([
            'is_active' => !$candidate->user->is_active
        ]);

        $status = $candidate->user->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        return back()->with('success', $status . ' حساب المرشح بنجاح');
    }

    public function deleteCandidate(Candidate $candidate)
    {
        // Delete associated images
        if ($candidate->profile_image && Storage::disk('public')->exists($candidate->profile_image)) {
            Storage::disk('public')->delete($candidate->profile_image);
        }

        if ($candidate->profile_banner_image && Storage::disk('public')->exists($candidate->profile_banner_image)) {
            Storage::disk('public')->delete($candidate->profile_banner_image);
        }

        $candidate->user->delete(); // Will cascade delete candidate
        return redirect()->route('admin.candidates.index')
            ->with('success', 'تم حذف المرشح بنجاح');
    }

    public function removeCandidateProfileImage(Candidate $candidate)
    {
        if ($candidate->profile_image && Storage::disk('public')->exists($candidate->profile_image)) {
            Storage::disk('public')->delete($candidate->profile_image);
        }

        $candidate->update(['profile_image' => null]);

        return back()->with('success', 'تم حذف صورة الملف الشخصي بنجاح');
    }

    public function removeCandidateProfileBannerImage(Candidate $candidate)
    {
        if ($candidate->profile_banner_image && Storage::disk('public')->exists($candidate->profile_banner_image)) {
            Storage::disk('public')->delete($candidate->profile_banner_image);
        }

        $candidate->update(['profile_banner_image' => null]);

        return back()->with('success', 'تم حذف صورة الغلاف بنجاح');
    }

    /**
     * Import candidates from Excel file with detailed error reporting
     */
    public function importCandidates(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ], [
            'excel_file.required' => 'يرجى اختيار ملف للاستيراد',
            'excel_file.file' => 'الملف المرفوع غير صالح',
            'excel_file.mimes' => 'نوع الملف غير مدعوم. يرجى استخدام ملفات Excel (.xlsx, .xls) أو CSV',
            'excel_file.max' => 'حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت'
        ]);

        try {
            $updateExisting = $request->has('update_existing');

            // Start import process
            $import = new CandidatesImport($updateExisting);
            Excel::import($import, $request->file('excel_file'));

            $results = $import->getImportResults();

            // Generate success message
            $message = $this->generateImportMessage($results);

            // Store detailed results in session for display
            session([
                'import_results' => $results,
                'import_detailed_report' => $import->getDetailedErrorReport()
            ]);

            // Determine if this is a success or warning based on results
            if ($results['failed'] > 0) {
                return redirect()->route('admin.candidates.index')
                    ->with('warning', $message)
                    ->with('show_import_details', true);
            } else {
                return redirect()->route('admin.candidates.index')
                    ->with('success', $message)
                    ->with('show_import_details', $results['warnings'] > 0);
            }

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Handle Laravel Excel validation errors
            $errorMessage = 'أخطاء في التحقق من صحة البيانات:<br>';
            foreach ($e->failures() as $failure) {
                $errorMessage .= "صف {$failure->row()}: " . implode(', ', $failure->errors()) . "<br>";
            }

            return redirect()->route('admin.candidates.index')
                ->with('error', $errorMessage);

        } catch (\Exception $e) {
            // Log the full error for debugging
            \Log::error('Import error: ' . $e->getMessage(), [
                'file' => $request->file('excel_file')->getClientOriginalName(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage();

            // Add technical details for admin users
            if (auth()->user()->isAdmin()) {
                $errorMessage .= '<br><small class="text-muted">تفاصيل تقنية: ' . get_class($e) . '</small>';
            }

            return redirect()->route('admin.candidates.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Generate detailed import success/warning message
     */
    protected function generateImportMessage($results)
    {
        $message = "";

        if ($results['successful'] > 0) {
            $message .= "✅ تم استيراد {$results['successful']} مرشح جديد بنجاح";
        }

        if ($results['updated'] > 0) {
            $message .= ($results['successful'] > 0 ? " | " : "") . "🔄 تم تحديث {$results['updated']} مرشح موجود";
        }

        if ($results['failed'] > 0) {
            $message .= ($results['successful'] > 0 || $results['updated'] > 0 ? " | " : "") .
                "❌ فشل في استيراد {$results['failed']} صف";
        }

        if ($results['warnings'] > 0) {
            $message .= " | ⚠️ {$results['warnings']} تحذير";
        }

        $successRate = $results['summary']['success_rate'] ?? 0;
        $message .= " | معدل النجاح: {$successRate}%";

        // Add most common errors summary
        if (!empty($results['summary']['most_common_errors'])) {
            $message .= "<br><small>الأخطاء الأكثر شيوعاً: ";
            $errorSummary = [];
            foreach ($results['summary']['most_common_errors'] as $type => $count) {
                $errorSummary[] = "{$type} ({$count})";
            }
            $message .= implode(', ', $errorSummary) . "</small>";
        }

        return $message;
    }

    /**
     * Download detailed import results as text file
     */
    public function downloadImportReport()
    {
        $report = session('import_detailed_report', 'لا يوجد تقرير متاح');
        $filename = 'import_report_' . now()->format('Y-m-d_H-i-s') . '.txt';

        return response($report)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export candidates to Excel
     */
    public function exportCandidates()
    {
        try {
            $fileName = 'candidates_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new CandidatesExport, $fileName);

        } catch (\Exception $e) {
            return redirect()->route('admin.candidates.index')
                ->with('error', 'حدث خطأ أثناء التصدير: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for importing candidates
     */
    public function downloadTemplate()
    {
        try {
            $fileName = 'candidates_template.xlsx';

            return Excel::download(new CandidatesTemplateExport, $fileName);

        } catch (\Exception $e) {
            return redirect()->route('admin.candidates.index')
                ->with('error', 'حدث خطأ أثناء تحميل النموذج: ' . $e->getMessage());
        }
    }

    /**
     * Debug Excel file columns (temporary method for troubleshooting)
     */
    public function debugExcelColumns(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new class implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                use \Maatwebsite\Excel\Concerns\Importable;

                public $columns = [];

                public function collection(\Illuminate\Support\Collection $rows)
                {
                    $firstRow = $rows->first();
                    if ($firstRow) {
                        $this->columns = array_keys($firstRow->toArray());
                    }
                }
            };

            Excel::import($import, $request->file('excel_file'));

            $debugInfo = [
                'file_name' => $request->file('excel_file')->getClientOriginalName(),
                'columns_found' => $import->columns,
                'columns_count' => count($import->columns),
                'expected_columns' => [
                    'first_name',
                    'last_name',
                    'email',
                    'constituency_id',
                    'party_bloc_name',
                    'phone',
                    'biography'
                ]
            ];

            return response()->json($debugInfo);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    // EDUCATION CRUD METHODS - FIXED
    
    public function addEducation(Request $request, Candidate $candidate)
    {
        $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'end_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'description' => ['nullable', 'string'],
        ]);

        $education = $candidate->education()->create($request->only([
            'degree',
            'institution',
            'field_of_study',
            'start_year',
            'end_year',
            'description'
        ]));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المؤهل العلمي بنجاح',
                'education' => $education
            ]);
        }

        return back()->with('success', 'تم إضافة المؤهل العلمي بنجاح');
    }

    public function editEducation(Candidate $candidate, Education $education)
    {
        // Check if education belongs to this candidate
        if ($education->candidate_id !== $candidate->id) {
            abort(404);
        }

        // If it's an AJAX request, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'education' => $education
            ]);
        }

        // Otherwise, redirect back to the candidate show page with the education data in session
        return redirect()->route('admin.candidates.show', $candidate)
            ->with('education_edit', $education->toArray());
    }

    public function updateEducation(Request $request, Candidate $candidate, Education $education)
    {
        // Check if education belongs to this candidate
        if ($education->candidate_id !== $candidate->id) {
            abort(404);
        }

        $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'end_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'description' => ['nullable', 'string'],
        ]);

        $education->update($request->only([
            'degree',
            'institution',
            'field_of_study',
            'start_year',
            'end_year',
            'description'
        ]));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المؤهل العلمي بنجاح',
                'education' => $education
            ]);
        }

        return back()->with('success', 'تم تحديث المؤهل العلمي بنجاح');
    }

    public function deleteEducation(Candidate $candidate, Education $education)
    {
        // Check if education belongs to this candidate
        if ($education->candidate_id !== $candidate->id) {
            abort(404);
        }

        $education->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المؤهل العلمي بنجاح'
            ]);
        }

        return back()->with('success', 'تم حذف المؤهل العلمي بنجاح');
    }
}