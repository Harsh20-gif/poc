<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'designation',
        'department',
        'status',
        'joining_date',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
        ];
    }

    /**
     * Leads assigned to this staff member.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    /**
     * Count of active leads.
     */
    public function activeLeadsCount(): int
    {
        return $this->leads()
            ->whereNotIn('status', ['completed', 'lost'])
            ->count();
    }
}
