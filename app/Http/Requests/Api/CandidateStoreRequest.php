<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CandidateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // User fields
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            
            // Required candidate fields
            'constituency_id' => ['required', 'exists:constituencies,id'],
            'party_bloc_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'biography' => ['required', 'string'],
            
            // Optional candidate fields
            'list_number' => ['nullable', 'integer'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'achievements' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'campaign_slogan' => ['nullable', 'string', 'max:255'],
            'voter_promises' => ['nullable', 'string'],
            
            // Image uploads
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'profile_banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            
            // Social links
            'facebook_link' => ['nullable', 'string', 'max:255'],
            'linkedin_link' => ['nullable', 'string', 'max:255'],
            'instagram_link' => ['nullable', 'string', 'max:255'],
            'twitter_link' => ['nullable', 'string', 'max:255'],
            'youtube_link' => ['nullable', 'string', 'max:255'],
            'tiktok_link' => ['nullable', 'string', 'max:255'],
            'website_link' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['required', 'string', 'max:255'],
            // Education (array of education records)
            'education' => ['nullable', 'array'],
            'education.*.degree' => ['required_with:education', 'string', 'max:255'],
            'education.*.institution' => ['required_with:education', 'string', 'max:255'],
            'education.*.field_of_study' => ['nullable', 'string', 'max:255'],
            'education.*.start_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'education.*.end_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'education.*.description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'اسم العائلة مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'constituency_id.required' => 'الدائرة الانتخابية مطلوبة',
            'constituency_id.exists' => 'الدائرة الانتخابية المختارة غير موجودة',
            'party_bloc_name.required' => 'اسم الحزب/الكتلة مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'biography.required' => 'السيرة الذاتية مطلوبة',
            'profile_image.image' => 'يجب أن تكون الصورة الشخصية ملف صورة',
            'profile_image.mimes' => 'يجب أن تكون الصورة الشخصية بصيغة jpeg أو png أو jpg أو gif',
            'profile_image.max' => 'يجب ألا يتجاوز حجم الصورة الشخصية 2 ميجابايت',
            'profile_banner_image.image' => 'يجب أن تكون صورة الغلاف ملف صورة',
            'profile_banner_image.mimes' => 'يجب أن تكون صورة الغلاف بصيغة jpeg أو png أو jpg أو gif',
            'profile_banner_image.max' => 'يجب ألا يتجاوز حجم صورة الغلاف 5 ميجابايت',
            'education.*.degree.required_with' => 'الدرجة العلمية مطلوبة عند إضافة التعليم',
            'education.*.institution.required_with' => 'المؤسسة التعليمية مطلوبة عند إضافة التعليم',
        ];
    }
}