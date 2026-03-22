<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model {
  protected $fillable = [
    'user_id', 'action', 'entity_type', 'entity_id', 'route', 'ip', 'user_agent', 'meta', 'changes',
  ];

  protected $casts = [
    'meta' => 'array',
    'changes' => 'array',
  ];

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function actionDescription(): string {
    $user = $this->user;
    $name = $user?->username ?? $user?->name ?? 'Unknown';
    $role = match ($user?->role ?? -1) {
      0 => 'Patient',
      1 => 'Secretary',
      2 => 'Doctor',
      default => 'User',
    };

    $pid = $this->meta['pid'] ?? null;
    $pidText = $pid ? " with {$pid}" : '';
    $date = $this->created_at->format('F j, Y');
    $time = $this->created_at->format('g:i A');

    return match ($this->action) {
      'patient_record.create'       => "{$role} {$name} {$pidText} added his/her own record on {$date} at {$time}",
      'patient_record.create_staff' => "{$role} {$name} added a patient record{$pidText} on {$date} at {$time}",
      'patient_record.update'       => "{$role} {$name} updated a patient record{$pidText} on {$date} at {$time}",
      'patient_record.bind'         => "{$role} {$name} linked to existing patient record{$pidText} on {$date} at {$time}",
      'patient.bind_user'           => "{$role} {$name} linked user account to patient record on {$date} at {$time}",
      'appointment_created'         => "{$role} {$name} created an appointment on {$date} at {$time}",
      'appointment_updated'         => "{$role} {$name} updated an appointment on {$date} at {$time}",
      default                       => "{$role} {$name} performed '{$this->action}' on {$date} at {$time}",
    };
  }
}
