<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = ['id'];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function sawResult()
    {
        return $this->hasOne(SawResult::class);
    }

    public function task()
    {
        return $this->hasOne(MaintenanceTask::class);
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }
}
