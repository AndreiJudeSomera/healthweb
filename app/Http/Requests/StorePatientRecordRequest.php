<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRecordRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }

  public function rules(): array {
    return [
      'last_name' => ['required', 'string', 'max:255'],
      'first_name' => ['required', 'string', 'max:255'],
      'middle_name' => ['nullable', 'string', 'max:255'],

      'gender' => ['required', 'in:male,female'],
      'date_of_birth' => ['required', 'date', 'before:today'],

      'nationality' => ['nullable', 'string'],
      'contact_number' => ['required', 'string', 'max:50'],
      'address' => ['required', 'string', 'max:255'],

      'guardian_name' => ['nullable', 'string', 'max:255'],
      'guardian_relation' => ['nullable', 'string'],
      'guardian_contact' => ['nullable', 'string'],

      'allergy' => ['nullable', 'string', 'max:255'],
      'alcohol' => ['nullable', 'string', 'max:255', 'in:never,occasional,heavy'],
      'years_of_smoking' => ['nullable', 'integer', 'min:0', 'max:100'],
      'illicit_drug_use' => ['nullable', 'string', 'max:255'],

      'hypertension' => ['nullable', 'boolean'],
      'asthma' => ['nullable', 'boolean'],
      'diabetes' => ['nullable', 'boolean'],
      'cancer' => ['nullable', 'boolean'],
      'thyroid' => ['nullable', 'boolean'],

      'patient_type' => ['nullable', 'string', 'max:50', 'in:old,new'],
   
      'others' => ['nullable', 'string', 'max:255'],
    ];
  }

  protected function prepareForValidation(): void {
    // Unchecked checkboxes become false

    
    foreach (['hypertension', 'asthma', 'diabetes', 'thyroid', 'cancer'] as $k) {
      $this->merge([$k => $this->boolean($k)]);
    }
  }

  public function messages(): array {
    return [
      'date_of_birth.before' => 'Date of birth must be in the past',
      'years_of_smoking.min' => 'Years of smoking cannot be negative',
    ];
  }
}
