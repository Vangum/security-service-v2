<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'department' => ['required'],
            'birth_date' => ['required', 'date', 'before:today'],
            'position' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/'],
            'entry_datetime' => ['required', 'date'],
            'exit_datetime' => ['required', 'date', 'after:entry_datetime'],
            'remarks' => ['nullable', 'string', 'max:256'],

            'document_type' => ['required', 'string', 'in:passport,license,other'],

            'passport_series' => ['nullable', 'required_if:document_type,passport', 'prohibited_unless:document_type,passport', 'digits:4'],
            'passport_number' => ['nullable', 'required_if:document_type,passport', 'prohibited_unless:document_type,passport', 'digits:6'],
            'passport_issue_date' => ['nullable', 'required_if:document_type,passport', 'prohibited_unless:document_type,passport', 'date', 'before_or_equal:today'],
            'passport_issued_by' => ['nullable', 'required_if:document_type,passport', 'prohibited_unless:document_type,passport', 'string', 'max:250'],
            'passport_department_code' => ['nullable', 'required_if:document_type,passport', 'prohibited_unless:document_type,passport', 'regex:/^\d{3}-\d{3}$/'],

            'license_series_number' => ['nullable', 'required_if:document_type,license', 'prohibited_unless:document_type,license', 'digits:10'],
            'license_issue_date' => ['nullable', 'required_if:document_type,license', 'prohibited_unless:document_type,license', 'date', 'before_or_equal:today'],
            'license_region' => ['nullable', 'required_if:document_type,license', 'prohibited_unless:document_type,license', 'string', 'max:150'],
            'license_issued_by' => ['nullable', 'required_if:document_type,license', 'prohibited_unless:document_type,license', 'string', 'max:250'],

            'other_document_name' => ['nullable', 'required_if:document_type,other', 'prohibited_unless:document_type,other', 'string', 'max:150'],
            'other_series_number' => ['nullable', 'required_if:document_type,other', 'prohibited_unless:document_type,other', 'string', 'max:50'],
            'other_issue_date' => ['nullable', 'required_if:document_type,other', 'prohibited_unless:document_type,other', 'date', 'before_or_equal:today'],
            'other_issued_by' => ['nullable', 'required_if:document_type,other', 'prohibited_unless:document_type,other', 'string', 'max:250'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Номер телефона должен быть в формате +7(000)000-00-00.',
            'passport_series.digits' => 'Серия паспорта должна содержать 4 цифры.',
            'passport_number.digits' => 'Номер паспорта должен содержать 6 цифр.',
            'passport_department_code.regex' => 'Код подразделения должен быть в формате 000-000.',

            'birth_date.before' => 'Дата рождения должна быть в прошлом.',
            'exit_datetime.after' => 'Время выхода должно быть после времени входа.',

            'passport_series.required_if' => 'Поле серия обязательно для заполнения при выборе паспорта.',
            'passport_number.required_if' => 'Поле номер обязательно для заполнения при выборе паспорта.',
            'passport_issue_date.required_if' => 'Поле дата выдачи обязательно для заполнения при выборе паспорта.',
            'passport_issue_date.before_or_equal' => 'Дата выдачи паспорта не может быть в будущем.',
            'passport_issued_by.required_if' => 'Поле кем выдан обязательно для заполнения при выборе паспорта.',
            'passport_department_code.required_if' => 'Поле код подразделения обязательно для заполнения при выборе паспорта.',

            'passport_series.prohibited_unless' => 'Поле серия должно быть пустым, если паспорт не выбран.',
            'passport_number.prohibited_unless' => 'Поле номер должно быть пустым, если паспорт не выбран.',
            'passport_issue_date.prohibited_unless' => 'Поле дата выдачи должно быть пустым, если паспорт не выбран.',
            'passport_issued_by.prohibited_unless' => 'Поле кем выдан должно быть пустым, если паспорт не выбран.',
            'passport_department_code.prohibited_unless' => 'Поле код подразделения должно быть пустым, если паспорт не выбран.',

            'license_series_number.required_if' => 'Поле серия и номер обязательно для заполнения при выборе водительского удостоверения.',
            'license_issue_date.required_if' => 'Поле дата выдачи обязательно для заполнения при выборе водительского удостоверения.',
            'license_issue_date.before_or_equal' => 'Дата выдачи водительского удостоверения не может быть в будущем.',
            'license_region.required_if' => 'Поле регион обязательно для заполнения при выборе водительского удостоверения.',
            'license_issued_by.required_if' => 'Поле кем выдан обязательно для заполнения при выборе водительского удостоверения.',

            'license_series_number.prohibited_unless' => 'Поле серия и номер должно быть пустым, если водительское удостоверение не выбрано.',
            'license_issue_date.prohibited_unless' => 'Поле дата выдачи должно быть пустым, если водительское удостоверение не выбрано.',
            'license_region.prohibited_unless' => 'Поле регион должно быть пустым, если водительское удостоверение не выбрано.',
            'license_issued_by.prohibited_unless' => 'Поле кем выдан должно быть пустым, если водительское удостоверение не выбрано.',

            'other_document_name.required_if' => 'Поле наименование документа обязательно для заполнения при выборе другого документа.',
            'other_series_number.required_if' => 'Поле серия и номер обязательно для заполнения при выборе другого документа.',
            'other_issue_date.required_if' => 'Поле дата выдачи обязательно для заполнения при выборе другого документа.',
            'other_issue_date.before_or_equal' => 'Дата выдачи другого документа не может быть в будущем.',
            'other_issued_by.required_if' => 'Поле кем выдан обязательно для заполнения при выборе другого документа.',

            'other_document_name.prohibited_unless' => 'Поле наименование документа должно быть пустым, если другой документ не выбран.',
            'other_series_number.prohibited_unless' => 'Поле серия и номер должно быть пустым, если другой документ не выбран.',
            'other_issue_date.prohibited_unless' => 'Поле дата выдачи должно быть пустым, если другой документ не выбран.',
            'other_issued_by.prohibited_unless' => 'Поле кем выдан должно быть пустым, если другой документ не выбран.',
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'ФИО',
            'department_id' => 'отдел',
            'birth_date' => 'дата рождения',
            'position' => 'должность',
            'phone' => 'номер телефона',
            'entry_datetime' => 'дата и время входа',
            'exit_datetime' => 'дата и время выхода',
            'remarks' => 'замечание',

            'document_type' => 'тип документа',

            'passport_series' => 'серия',
            'passport_number' => 'номер',
            'passport_issue_date' => 'дата выдачи',
            'passport_issued_by' => 'кем выдан',
            'passport_department_code' => 'код подразделения',

            'license_series_number' => 'серия и номер',
            'license_issue_date' => 'дата выдачи',
            'license_region' => 'регион',
            'license_issued_by' => 'кем выдан',

            'other_document_name' => 'наименование документа',
            'other_series_number' => 'серия и номер',
            'other_issue_date' => 'дата выдачи',
            'other_issued_by' => 'кем выдан',
        ];
    }
}
