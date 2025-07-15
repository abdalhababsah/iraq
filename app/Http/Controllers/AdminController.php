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
        // Basic candidate statistics
        $totalCandidates = Candidate::count();
        $activeCandidates = User::where('role', 'candidate')->where('is_active', true)->count();

        // Constituency statistics
        $totalConstituencies = Constituency::count();

        // Recent registrations (last 7 days)
        $recentRegistrations = Candidate::where('created_at', '>=', now()->subDays(7))->count();

        // Recent candidates for the table (last 10, with relationships)
        $recentCandidates = Candidate::with(['user', 'constituency'])
            ->latest()
            ->take(10)
            ->get();

        // Additional statistics for charts/analysis
        $candidatesByConstituency = Candidate::with('constituency')
            ->selectRaw('constituency_id, count(*) as total')
            ->groupBy('constituency_id')
            ->get();

        // Weekly registration trend (last 4 weeks)
        $weeklyRegistrations = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            $weeklyRegistrations[] = [
                'week' => $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d'),
                'count' => Candidate::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count()
            ];
        }

        // Party/Bloc statistics
        $topParties = Candidate::selectRaw('party_bloc_name, count(*) as total')
            ->groupBy('party_bloc_name')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard.admin.dashboard', compact(
            'totalCandidates',
            'activeCandidates',
            'totalConstituencies',
            'recentRegistrations',
            'recentCandidates',
            'candidatesByConstituency',
            'weeklyRegistrations',
            'topParties'
        ));
    }


    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => $request->has('is_active') ? true : false,
        ]);
    
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم إنشاء المدير بنجاح']);
        }
    
        return redirect()->route('admin.admins.index')->with('success', 'تم إنشاء المدير بنجاح');
    }
    
    public function updateAdmin(Request $request, User $admin)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);
    
        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'is_active' => $request->has('is_active') ? true : false,
        ];
    
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
    
        $admin->update($updateData);
    
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث المدير بنجاح']);
        }
    
        return redirect()->route('admin.admins.index')->with('success', 'تم تحديث المدير بنجاح');
    }
    
    public function toggleAdminStatus(User $admin)
    {
        // Prevent admin from deactivating themselves
        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admins.index')->with('error', 'لا يمكنك إلغاء تفعيل حسابك الشخصي');
        }
    
        $admin->update([
            'is_active' => !$admin->is_active
        ]);
    
        $status = $admin->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        return redirect()->route('admin.admins.index')->with('success', $status . ' المدير بنجاح');
    }
    
    public function deleteAdmin(User $admin)
    {
        // Prevent admin from deleting themselves
        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admins.index')->with('error', 'لا يمكنك حذف حسابك الشخصي');
        }
    
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'تم حذف المدير بنجاح');
    }

    public function admins()
    {
        $admins = User::where('role', 'admin')->get();
        return view('dashboard.admin.admins.index', compact('admins'));
    }


}