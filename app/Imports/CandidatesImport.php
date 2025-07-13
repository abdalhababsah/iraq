<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Constituency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class CandidatesImport implements ToCollection, WithHeadingRow
{
    use Importable;

    protected $updateExisting = false;
    protected $columnMappings = [];
    protected $importResults = [
        'total' => 0,
        'successful' => 0,
        'failed' => 0,
        'updated' => 0,
        'warnings' => 0,
        'errors' => [],
        'warnings_list' => [],
        'summary' => []
    ];

    protected $constituencies = [];
    protected $existingEmails = [];

    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
        
        // Cache constituencies for better performance and detailed error messages
        $this->constituencies = Constituency::pluck('name', 'id')->toArray();
        
        // Cache existing emails for duplicate detection
        $this->existingEmails = User::where('role', 'candidate')->pluck('id', 'email')->toArray();
    }

    public function collection(Collection $rows)
    {
        $this->importResults['total'] = $rows->count();

        // First pass: validate structure and collect issues
        $this->validateDataStructure($rows);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because of heading row and 0-based index
            
            try {
                $this->processRow($row, $rowNumber);
            } catch (\Exception $e) {
                $this->importResults['failed']++;
                $this->addDetailedError($rowNumber, $e->getMessage(), $row->toArray(), 'PROCESSING_ERROR');
            }
        }

        $this->generateSummary();
    }

    protected function validateDataStructure(Collection $rows)
    {
        $firstRow = $rows->first();
        
        if (!$firstRow) {
            throw new \Exception('الملف فارغ أو لا يحتوي على بيانات صالحة');
        }

        // Debug: Log what columns we actually received
        $actualColumns = array_keys($firstRow->toArray());
        \Log::info('Actual Excel columns received:', $actualColumns);

        // Define column mappings (flexible matching for both old and new formats)
        $columnMappings = [
            'first_name' => ['first_name', 'First Name*', 'الاسم الأول', 'name', 'Name*', 'اسم المستخدم'],
            'last_name' => ['last_name', 'Last Name*', 'الاسم الأخير', 'اللقب'],
            'email' => ['email', 'Email*', 'البريد الإلكتروني', 'ايميل'],
            'constituency_id' => ['constituency_id', 'Constituency ID*', 'رقم الدائرة', 'constituency id'],
            'party_bloc_name' => ['party_bloc_name', 'Party/Bloc Name*', 'اسم الكتلة', 'party bloc name'],
            'phone' => ['phone', 'Phone*', 'رقم الهاتف', 'الهاتف'],
            'biography' => ['biography', 'Biography*', 'السيرة الذاتية', 'السيرة']
        ];

        $missingColumns = [];
        $foundMappings = [];

        // Check for new format (first_name + last_name) OR old format (name + full_name)
        $hasNewFormat = false;
        $hasOldFormat = false;

        // Check if we have first_name and last_name (new format)
        foreach (['first_name', 'last_name'] as $column) {
            foreach ($columnMappings[$column] as $possible) {
                if (in_array($possible, $actualColumns)) {
                    $foundMappings[$column] = $possible;
                    $hasNewFormat = true;
                    break;
                }
            }
        }

        // Check if we have name and full_name (old format)
        if (in_array('name', $actualColumns) && in_array('full_name', $actualColumns)) {
            $hasOldFormat = true;
            // Map old format to new format
            $foundMappings['old_name'] = 'name';
            $foundMappings['old_full_name'] = 'full_name';
        }

        // If we have neither complete format, check other required columns
        if (!$hasNewFormat && !$hasOldFormat) {
            $missingColumns[] = 'first_name and last_name OR name and full_name';
        }

        // Check other required columns
        foreach (['email', 'constituency_id', 'party_bloc_name', 'phone', 'biography'] as $required) {
            $found = false;
            foreach ($columnMappings[$required] as $possible) {
                if (in_array($possible, $actualColumns)) {
                    $foundMappings[$required] = $possible;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $missingColumns[] = $required . ' (' . implode(', ', $columnMappings[$required]) . ')';
            }
        }

        if (!empty($missingColumns)) {
            $actualColumnsStr = implode(', ', $actualColumns);
            throw new \Exception("أعمدة مطلوبة مفقودة: " . implode(', ', $missingColumns) . 
                               "\n\nالأعمدة الموجودة فعلياً: " . $actualColumnsStr . 
                               "\n\nتأكد من أن الصف الأول يحتوي على أسماء الأعمدة الصحيحة");
        }

        // Store the mappings and format info for later use
        $this->columnMappings = $foundMappings;
        $this->columnMappings['_format'] = $hasOldFormat ? 'old' : 'new';
    }

    protected function processRow($row, $rowNumber)
    {
        $rowData = $row->toArray();
        
        // Map the columns to our expected names
        $mappedData = [];
        
        // Handle different formats
        $format = $this->columnMappings['_format'] ?? 'new';
        
        if ($format === 'old') {
            // Old format: name + full_name
            $fullName = $rowData[$this->columnMappings['old_full_name']] ?? '';
            $nameParts = explode(' ', trim($fullName), 2);
            
            $mappedData['first_name'] = $nameParts[0] ?? '';
            $mappedData['last_name'] = $nameParts[1] ?? '';
            
            // If full_name is empty but name exists, use name as first_name
            if (empty($mappedData['first_name']) && !empty($rowData[$this->columnMappings['old_name']] ?? '')) {
                $mappedData['first_name'] = $rowData[$this->columnMappings['old_name']];
            }
        } else {
            // New format: first_name + last_name
            foreach (['first_name', 'last_name'] as $field) {
                if (isset($this->columnMappings[$field])) {
                    $mappedData[$field] = $rowData[$this->columnMappings[$field]] ?? null;
                }
            }
        }
        
        // Map other required columns
        foreach (['email', 'constituency_id', 'party_bloc_name', 'phone', 'biography'] as $field) {
            if (isset($this->columnMappings[$field])) {
                $mappedData[$field] = $rowData[$this->columnMappings[$field]] ?? null;
            }
        }
        
        // Add optional columns directly if they exist
        $optionalColumns = [
            'password', 'list_number', 'current_position',
            'achievements', 'additional_info', 'experience', 'skills',
            'campaign_slogan', 'voter_promises', 'is_active'
        ];
        
        foreach ($optionalColumns as $column) {
            if (isset($rowData[$column])) {
                $mappedData[$column] = $rowData[$column];
            }
        }

        $errors = [];
        $warnings = [];

        // Step 1: Basic validation
        $validationErrors = $this->validateBasicFields($mappedData);
        if (!empty($validationErrors)) {
            throw new \Exception('أخطاء في التحقق من صحة البيانات: ' . implode(' | ', $validationErrors));
        }

        // Step 2: Business logic validation
        $businessErrors = $this->validateBusinessLogic($mappedData, $rowNumber);
        if (!empty($businessErrors)) {
            throw new \Exception('أخطاء في المنطق التجاري: ' . implode(' | ', $businessErrors));
        }

        // Step 3: Check for existing user
        $existingUser = User::where('email', $mappedData['email'])->first();
        
        if ($existingUser && !$this->updateExisting) {
            throw new \Exception("المستخدم موجود مسبقاً بالبريد الإلكتروني '{$mappedData['email']}' ولم يتم تفعيل خيار التحديث");
        }

        // Step 4: Process user creation/update
        $userResult = $this->processUser($existingUser, $mappedData, $rowNumber);
        $user = $userResult['user'];
        $isUpdate = $userResult['isUpdate'];

        // Step 5: Process candidate creation/update
        $this->processCandidate($user, $mappedData, $isUpdate, $rowNumber);

        // Step 6: Update counters
        if ($isUpdate) {
            $this->importResults['updated']++;
        } else {
            $this->importResults['successful']++;
        }

        // Add any warnings collected during processing
        if (!empty($warnings)) {
            $this->importResults['warnings']++;
            $this->importResults['warnings_list'][] = [
                'row' => $rowNumber,
                'warnings' => $warnings,
                'data' => $mappedData
            ];
        }
    }

    protected function validateBasicFields($rowData)
    {
        $errors = [];

        // First name validation
        if (empty(trim($rowData['first_name'] ?? ''))) {
            $errors[] = 'الاسم الأول مطلوب';
        } elseif (strlen($rowData['first_name']) > 255) {
            $errors[] = 'الاسم الأول طويل جداً (الحد الأقصى 255 حرف)';
        }

        // Last name validation
        if (empty(trim($rowData['last_name'] ?? ''))) {
            $errors[] = 'الاسم الأخير مطلوب';
        } elseif (strlen($rowData['last_name']) > 255) {
            $errors[] = 'الاسم الأخير طويل جداً (الحد الأقصى 255 حرف)';
        }

        // Email validation
        if (empty(trim($rowData['email'] ?? ''))) {
            $errors[] = 'البريد الإلكتروني مطلوب';
        } elseif (!filter_var($rowData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "البريد الإلكتروني '{$rowData['email']}' غير صحيح";
        }

        // Phone validation
        if (empty(trim($rowData['phone'] ?? ''))) {
            $errors[] = 'رقم الهاتف مطلوب';
        } elseif (!preg_match('/^[\d\+\-\s\(\)]+$/', $rowData['phone'])) {
            $errors[] = "رقم الهاتف '{$rowData['phone']}' يحتوي على أحرف غير صالحة";
        }

        // Biography validation
        if (empty(trim($rowData['biography'] ?? ''))) {
            $errors[] = 'السيرة الذاتية مطلوبة';
        }

        // Party bloc name validation
        if (empty(trim($rowData['party_bloc_name'] ?? ''))) {
            $errors[] = 'اسم الكتلة أو الحزب مطلوب';
        }

        return $errors;
    }

    protected function validateBusinessLogic($rowData, $rowNumber)
    {
        $errors = [];

        // Constituency validation
        $constituencyId = $rowData['constituency_id'] ?? null;
        if (empty($constituencyId)) {
            $errors[] = 'رقم الدائرة الانتخابية مطلوب';
        } elseif (!is_numeric($constituencyId)) {
            $errors[] = "رقم الدائرة الانتخابية '{$constituencyId}' يجب أن يكون رقماً";
        } elseif (!isset($this->constituencies[$constituencyId])) {
            $availableIds = implode(', ', array_keys($this->constituencies));
            $errors[] = "الدائرة الانتخابية رقم '{$constituencyId}' غير موجودة. الدوائر المتاحة: {$availableIds}";
        }

        // Email uniqueness check (for new users only)
        $email = $rowData['email'] ?? '';
        if (!$this->updateExisting && isset($this->existingEmails[$email])) {
            $errors[] = "البريد الإلكتروني '{$email}' مستخدم بالفعل من قبل مستخدم آخر (ID: {$this->existingEmails[$email]})";
        }

        // Password validation (for new users)
        $password = $rowData['password'] ?? '';
        if (empty($password) && !isset($this->existingEmails[$email])) {
            // This is a warning, not an error - we'll use default password
        } elseif (!empty($password) && strlen($password) < 6) {
            $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
        }

        // Is_active validation
        $isActive = $rowData['is_active'] ?? 1;
        if (!in_array($isActive, [0, 1, '0', '1', true, false, 'true', 'false'])) {
            $errors[] = "قيمة 'نشط' يجب أن تكون 0 أو 1، تم العثور على: '{$isActive}'";
        }

        return $errors;
    }

    protected function processUser($existingUser, $rowData, $rowNumber)
    {
        $isUpdate = false;
        
        if ($existingUser && $this->updateExisting) {
            // Update existing user
            $updateData = [
                'first_name' => $rowData['first_name'],
                'last_name' => $rowData['last_name'],
                'is_active' => $this->normalizeBoolean($rowData['is_active'] ?? true),
            ];
            
            $existingUser->update($updateData);
            $user = $existingUser;
            $isUpdate = true;
            
        } else {
            // Create new user
            $userData = [
                'first_name' => $rowData['first_name'],
                'last_name' => $rowData['last_name'],
                'email' => $rowData['email'],
                'password' => Hash::make($rowData['password'] ?? 'password123'),
                'role' => 'candidate',
                'is_active' => $this->normalizeBoolean($rowData['is_active'] ?? true),
                'email_verified_at' => now(),
            ];
            
            $user = User::create($userData);
            
            // Add to existing emails cache to prevent duplicates in same import
            $this->existingEmails[$rowData['email']] = $user->id;
        }

        return ['user' => $user, 'isUpdate' => $isUpdate];
    }

    protected function processCandidate($user, $rowData, $isUpdate, $rowNumber)
    {
        $candidateData = [
            'constituency_id' => $rowData['constituency_id'],
            'party_bloc_name' => $rowData['party_bloc_name'],
            'phone' => $rowData['phone'],
            'biography' => $rowData['biography'],
            'list_number' => $rowData['list_number'] ?? null,
            'current_position' => $rowData['current_position'] ?? null,
            'achievements' => $rowData['achievements'] ?? null,
            'additional_info' => $rowData['additional_info'] ?? null,
            'experience' => $rowData['experience'] ?? null,
            'skills' => $rowData['skills'] ?? null,
            'campaign_slogan' => $rowData['campaign_slogan'] ?? null,
            'voter_promises' => $rowData['voter_promises'] ?? null,
        ];

        if ($user->candidate) {
            $user->candidate->update($candidateData);
        } else {
            $candidateData['user_id'] = $user->id;
            Candidate::create($candidateData);
        }
    }

    protected function addDetailedError($rowNumber, $message, $data, $errorType = 'GENERAL_ERROR')
    {
        $this->importResults['errors'][] = [
            'row' => $rowNumber,
            'type' => $errorType,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'context' => $this->getErrorContext($data)
        ];
    }

    protected function getErrorContext($data)
    {
        $context = [];
        
        if (isset($data['email'])) {
            $context['email'] = $data['email'];
        }
        
        if (isset($data['first_name']) || isset($data['last_name'])) {
            $context['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        }
        
        if (isset($data['constituency_id'])) {
            $constituencyName = $this->constituencies[$data['constituency_id']] ?? 'غير معروف';
            $context['constituency'] = "ID: {$data['constituency_id']} ({$constituencyName})";
        }

        return $context;
    }

    protected function normalizeBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        
        return in_array(strtolower($value), ['1', 'true', 'yes', 'نعم', 'صحيح']);
    }

    protected function generateSummary()
    {
        $this->importResults['summary'] = [
            'total_processed' => $this->importResults['total'],
            'successful_imports' => $this->importResults['successful'],
            'updated_records' => $this->importResults['updated'],
            'failed_imports' => $this->importResults['failed'],
            'warnings_count' => $this->importResults['warnings'],
            'success_rate' => $this->importResults['total'] > 0 ? 
                round(($this->importResults['successful'] + $this->importResults['updated']) / $this->importResults['total'] * 100, 2) : 0,
            'most_common_errors' => $this->getMostCommonErrors(),
            'processing_time' => now()->format('Y-m-d H:i:s')
        ];
    }

    protected function getMostCommonErrors()
    {
        $errorTypes = [];
        
        foreach ($this->importResults['errors'] as $error) {
            $type = $error['type'] ?? 'UNKNOWN';
            $errorTypes[$type] = ($errorTypes[$type] ?? 0) + 1;
        }
        
        arsort($errorTypes);
        return array_slice($errorTypes, 0, 5, true);
    }

    public function getImportResults()
    {
        return $this->importResults;
    }

    public function getDetailedErrorReport()
    {
        $report = "=== تقرير مفصل عن أخطاء الاستيراد ===\n\n";
        
        $report .= "إحصائيات عامة:\n";
        $report .= "- إجمالي الصفوف: {$this->importResults['total']}\n";
        $report .= "- نجح: {$this->importResults['successful']}\n";
        $report .= "- تم تحديثه: {$this->importResults['updated']}\n";
        $report .= "- فشل: {$this->importResults['failed']}\n";
        $report .= "- تحذيرات: {$this->importResults['warnings']}\n\n";

        if (!empty($this->importResults['errors'])) {
            $report .= "=== تفاصيل الأخطاء ===\n";
            foreach ($this->importResults['errors'] as $error) {
                $report .= "صف رقم {$error['row']}:\n";
                $report .= "- نوع الخطأ: {$error['type']}\n";
                $report .= "- الرسالة: {$error['message']}\n";
                if (!empty($error['context'])) {
                    $report .= "- السياق: " . json_encode($error['context'], JSON_UNESCAPED_UNICODE) . "\n";
                }
                $report .= "- البيانات: " . json_encode($error['data'], JSON_UNESCAPED_UNICODE) . "\n";
                $report .= "---\n";
            }
        }

        return $report;
    }
}