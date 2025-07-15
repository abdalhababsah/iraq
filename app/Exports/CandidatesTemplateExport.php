<?php
// app/Exports/CandidatesTemplateExport.php

namespace App\Exports;

use App\Models\Constituency;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidatesTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return sample data rows with correct structure (first_name, last_name)
        return [
            [
                'أحمد',
                'محمد',
                'ahmed@exaple.com',
                'password123',
                1,
                'حزب التقدم والإصلاح',
                '07701234567',
                'محامي وناشط سياسي، عمل في مجال حقوق الإنسان لأكثر من 15 عامًا.',
                '001',
                'محامي ومستشار قانوني',
                'حاصل على جوائز في مجال حقوق الإنسان',
                'متحدث باللغة الإنجليزية والفرنسية',
                '15 سنة خبرة في المحاماة',
                'التفاوض، القيادة، حل النزاعات',
                'معًا نحو مستقبل أفضل',
                'تحسين الخدمات الحكومية وحماية حقوق المواطنين',
                'https://www.facebook.com/fatima.hsn',
                'https://www.linkedin.com/in/fatima-hsn',
                'https://www.instagram.com/fatima.hsn',
                'https://www.twitter.com/fatima.hsn',
                'https://www.youtube.com/fatima.hsn',
                'https://www.tiktok.com/fatima.hsn',
                'https://www.fatima.hsn.com',
                1
            ],
            [
                'فاطمة',
                'حسين',
                'fatima@eample.com',
                'password123',
                2,
                'تحالف الوحدة الوطنية',
                '07709846543',
                'أستاذة جامعية ومتخصصة في الاقتصاد، عملت في العديد من المشاريع التنموية.',
                '002',
                'أستاذة جامعية - كلية الاقتصاد',
                'نشر 25 بحث علمي في الاقتصاد الدولي',
                'حاصلة على دكتوراه في الاقتصاد',
                '12 سنة خبرة في التدريس الجامعي',
                'البحث العلمي، التحليل الاقتصادي، الإدارة',
                'اقتصاد قوي لمستقبل مزدهر',
                'تطوير القطاع الاقتصادي وخلق فرص عمل للشباب',
                'https://www.facebook.com/ali.hsn',
                'https://www.linkedin.com/in/ali-hsn',
                'https://www.instagram.com/ali.hsn',
                'https://www.twitter.com/ali.hsn',
                'https://www.youtube.com/ali.hsn',
                'https://www.tiktok.com/ali.hsn',
                'https://www.ali.hsn.com',
                1
            ],
            [
                'علي',
                'حسن',
                'ali@exampe.com',
                '',
                3,
                'تحالف المستقبل',
                '07712445678',
                'مهندس ورجل أعمال، ساهم في إعادة إعمار المناطق المتضررة في نينوى.',
                '',
                'مدير شركة الإعمار والتطوير',
                'قاد مشاريع إعمار بقيمة 50 مليون دولار',
                'خبرة واسعة في إدارة المشاريع الكبرى',
                '20 سنة خبرة في الهندسة والإعمار',
                'إدارة المشاريع، التخطيط العمراني، القيادة',
                'إعادة البناء والتطوير',
                'إعادة إعمار المناطق المتضررة وتوفير الخدمات الأساسية',
                'https://www.facebook.com/ali.hsn',
                'https://www.linkedin.com/in/ali-hsn',
                'https://www.instagram.com/ali.hsn',
                'https://www.twitter.com/ali.hsn',
                'https://www.youtube.com/ali.hsn',
                'https://www.tiktok.com/ali.hsn',
                'https://www.ali.hsn.com',
                1
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'password',
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
            'website_link',
            'is_active'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style headers
        $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $sheet->getStyle('A1:Q1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:Q1')->getFill()->getStartColor()->setRGB('E3F2FD');

        
        $sheet->getStyle('A2:Q2')->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A2:Q2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:Q2')->getFill()->getStartColor()->setRGB('F5F5F5');

        // Add comments/notes for important columns
        $sheet->getComment('A1')->getText()->createTextRun('الاسم الأول - مطلوب');
        $sheet->getComment('B1')->getText()->createTextRun('الاسم الأخير - مطلوب');
        $sheet->getComment('C1')->getText()->createTextRun('البريد الإلكتروني - يجب أن يكون فريدًا لكل مرشح - مطلوب');
        $sheet->getComment('D1')->getText()->createTextRun('كلمة المرور - اتركها فارغة لاستخدام كلمة مرور افتراضية');
        $sheet->getComment('E1')->getText()->createTextRun('رقم الدائرة الانتخابية - يجب أن تكون موجودة في النظام - مطلوب');
        $sheet->getComment('Q1')->getText()->createTextRun('حالة المرشح: 1 = نشط، 0 = غير نشط');

        // Add data validation for constituency_id (assuming max 20 constituencies)
        $validation = $sheet->getCell('E3')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
        $validation->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_BETWEEN);
        $validation->setFormula1('1');
        $validation->setFormula2('20');
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('خطأ في الدائرة الانتخابية');
        $validation->setError('يجب أن يكون رقم الدائرة الانتخابية بين 1 و 20');

        // Add data validation for is_active
        $validation = $sheet->getCell('Q3')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setFormula1('"0,1"');
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('قيمة غير صحيحة');
        $validation->setError('يجب أن تكون القيمة 0 أو 1');

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // first_name
            'B' => 20, // last_name
            'C' => 25, // email
            'D' => 15, // password
            'E' => 15, // constituency_id
            'F' => 25, // party_bloc_name
            'G' => 15, // phone
            'H' => 40, // biography
            'I' => 15, // list_number
            'J' => 25, // current_position
            'K' => 30, // achievements
            'L' => 30, // additional_info
            'M' => 30, // experience
            'N' => 20, // skills
            'O' => 25, // campaign_slogan
            'P' => 30, // voter_promises
            'Q' => 15, // facebook_link
            'R' => 15, // linkedin_link
            'S' => 15, // instagram_link
            'T' => 15, // twitter_link
            'U' => 15, // youtube_link
            'V' => 15, // tiktok_link
            'W' => 15, // website_link
            'X' => 15, // is_active
        ];
    }
}