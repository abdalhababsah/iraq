<?php

namespace App\Exports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidatesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Candidate::with(['user', 'constituency'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Constituency ID',
            'Constituency Name',
            'Party/Bloc Name',
            'Phone',
            'Biography',
            'List Number',
            'Current Position',
            'Achievements',
            'Additional Info',
            'Experience',
            'Skills',
            'Campaign Slogan',
            'Voter Promises',
            'Is Active',
            'Created At',
            'Updated At'
        ];
    }

    public function map($candidate): array
    {
        return [
            $candidate->id,
            $candidate->user->first_name,
            $candidate->user->last_name,
            $candidate->user->email,
            $candidate->constituency_id,
            $candidate->constituency->name ?? 'غير محدد',
            $candidate->party_bloc_name,
            $candidate->phone,
            $candidate->biography,
            $candidate->list_number,
            $candidate->current_position,
            $candidate->achievements,
            $candidate->additional_info,
            $candidate->experience,
            $candidate->skills,
            $candidate->campaign_slogan,
            $candidate->voter_promises,
            $candidate->user->is_active ? 'نشط' : 'غير نشط',
            $candidate->created_at->format('Y-m-d H:i:s'),
            $candidate->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}