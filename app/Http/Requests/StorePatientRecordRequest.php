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
  }public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $first  = strtolower(trim($this->first_name));
        $last   = strtolower(trim($this->last_name));
        $middle = strtolower(trim($this->middle_name ?? ''));
        $dob    = $this->date_of_birth;

        // Fetch patients with the same DOB (date_of_birth is not encrypted)
        $potentialDuplicates = \App\Models\PatientRecord::whereDate('date_of_birth', $dob)->get();

        foreach ($potentialDuplicates as $patient) {
            // Decrypt the names for comparison
            $existingFirst  = strtolower($patient->first_name); // your accessor already decrypts
            $existingLast   = strtolower($patient->last_name);
            $existingMiddle = strtolower($patient->middle_name ?? '');

            if ($first === $existingFirst && $last === $existingLast && $middle === $existingMiddle) {
                $validator->errors()->add('first_name', 'Patient record already exists.');
                break; // Stop after first match
            }
        }
    });
}
  // protected function prepareForValidation(): void {
  //   // Unchecked checkboxes become false

    
  //   foreach (['hypertension', 'asthma', 'diabetes', 'thyroid', 'cancer'] as $k) {
  //     $this->merge([$k => $this->boolean($k)]);
  //   }
  // }
  protected function prepareForValidation(): void {
    $this->merge([
        'first_name' => strtolower(trim($this->first_name)),
        'last_name' => strtolower(trim($this->last_name)),
        'middle_name' => $this->middle_name ? strtolower(trim($this->middle_name)) : null,
    ]);

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
