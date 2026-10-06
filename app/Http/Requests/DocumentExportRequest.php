<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DocumentExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'students' => 'required',
            'include_photo' => 'nullable|boolean',
            'items' => 'nullable|array',
            'group_by' => 'required|in:flat,student,student_module,student_module_field,module_student,module_field',
            'naming_format' => 'required|in:enroll_document,enroll_only,document_only,enroll_name_document',
            'separator' => 'required|in:underscore,hyphen,spaced_dash',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!$this->includePhoto() && empty($this->selectedItems())) {
                $validator->errors()->add(
                    'export',
                    'Please select at least one document type or enable student profile photos to export.'
                );
            }
        });
    }

    /**
     * Normalized options for the document export service.
     */
    public function exportOptions(): array
    {
        return [
            'students' => $this->input('students'),
            'include_photo' => $this->includePhoto(),
            'items' => $this->selectedItems(),
            'group_by' => $this->input('group_by'),
            'naming_format' => $this->input('naming_format'),
            'separator' => $this->input('separator'),
        ];
    }

    protected function includePhoto(): bool
    {
        return filter_var($this->input('include_photo', false), FILTER_VALIDATE_BOOLEAN);
    }

    protected function selectedItems(): array
    {
        return array_filter((array)$this->input('items', []));
    }
}
