<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Education;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample candidates
        $candidates = [
            [
                'user' => [
                    'first_name' => 'أحمد',
                    'last_name' => 'محمد',
                    'email' => 'ahmed@example.com',
                    'password' => 'password',
                    'role' => 'candidate',
                ],
                'candidate' => [
                    'constituency_id' => 1, // بغداد
                    'party_bloc_name' => 'حزب التقدم والإصلاح',
                    'phone' => '07701234567',
                    'biography' => 'محامي وناشط سياسي، عمل في مجال حقوق الإنسان لأكثر من 15 عامًا.',
                    'current_position' => 'محامي ومستشار قانوني',
                    'campaign_slogan' => 'معًا نحو مستقبل أفضل',
                ],
                'education' => [
                    [
                        'degree' => 'بكالوريوس',
                        'institution' => 'جامعة بغداد',
                        'field_of_study' => 'القانون',
                        'start_year' => 2005,
                        'end_year' => 2009,
                    ],
                    [
                        'degree' => 'ماجستير',
                        'institution' => 'جامعة بغداد',
                        'field_of_study' => 'القانون الدولي',
                        'start_year' => 2010,
                        'end_year' => 2012,
                    ],
                ]
            ],
            [
                'user' => [
                    'first_name' => 'فاطمة',
                    'last_name' => 'حسين',
                    'email' => 'fatima@example.com',
                    'password' => 'password',
                    'role' => 'candidate',
                ],
                'candidate' => [
                    'constituency_id' => 2, // البصرة
                    'party_bloc_name' => 'تحالف الوحدة الوطنية',
                    'phone' => '07709876543',
                    'biography' => 'أستاذة جامعية ومتخصصة في الاقتصاد، عملت في العديد من المشاريع التنموية.',
                    'current_position' => 'أستاذة جامعية - كلية الاقتصاد',
                    'campaign_slogan' => 'اقتصاد قوي لمستقبل مزدهر',
                ],
                'education' => [
                    [
                        'degree' => 'بكالوريوس',
                        'institution' => 'جامعة البصرة',
                        'field_of_study' => 'الاقتصاد',
                        'start_year' => 2003,
                        'end_year' => 2007,
                    ],
                    [
                        'degree' => 'ماجستير',
                        'institution' => 'جامعة البصرة',
                        'field_of_study' => 'الاقتصاد الدولي',
                        'start_year' => 2008,
                        'end_year' => 2010,
                    ],
                    [
                        'degree' => 'دكتوراه',
                        'institution' => 'جامعة بغداد',
                        'field_of_study' => 'الاقتصاد والتنمية المستدامة',
                        'start_year' => 2012,
                        'end_year' => 2016,
                    ],
                ]
            ],
            [
                'user' => [
                    'first_name' => 'علي',
                    'last_name' => 'حسن',
                    'email' => 'ali@example.com',
                    'password' => 'password',
                    'role' => 'candidate',
                ],
                'candidate' => [
                    'constituency_id' => 3, // نينوى
                    'party_bloc_name' => 'تحالف المستقبل',
                    'phone' => '07712345678',
                    'biography' => 'مهندس ورجل أعمال، ساهم في إعادة إعمار المناطق المتضررة في نينوى.',
                    'current_position' => 'مدير شركة الإعمار والتطوير',
                    'campaign_slogan' => 'إعادة البناء والتطوير',
                ],
                'education' => [
                    [
                        'degree' => 'بكالوريوس',
                        'institution' => 'جامعة الموصل',
                        'field_of_study' => 'الهندسة المدنية',
                        'start_year' => 2000,
                        'end_year' => 2004,
                    ],
                    [
                        'degree' => 'دبلوم عالي',
                        'institution' => 'معهد التخطيط الحضري',
                        'field_of_study' => 'التخطيط العمراني',
                        'start_year' => 2006,
                        'end_year' => 2007,
                    ],
                ]
            ],
        ];

        // Create the candidates and their education records
        foreach ($candidates as $candidateData) {
            // Create user
            $user = User::create([
                'first_name' => $candidateData['user']['first_name'],
                'last_name' => $candidateData['user']['last_name'],
                'email' => $candidateData['user']['email'],
                'password' => Hash::make($candidateData['user']['password']),
                'role' => $candidateData['user']['role'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create candidate
            $candidate = Candidate::create(array_merge(
                $candidateData['candidate'],
                ['user_id' => $user->id]
            ));

            // Create education records
            foreach ($candidateData['education'] as $educationData) {
                Education::create(array_merge(
                    $educationData,
                    ['candidate_id' => $candidate->id]
                ));
            }
        }
    }
}