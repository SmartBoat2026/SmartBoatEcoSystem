<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelStaff extends Model
{
    protected $table = 'panel_staff';

    protected $fillable = [
        'username', 'password', 'name', 'permissions', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function verifyPassword(string $password): bool
    {
        return $this->password === $password;
    }
}
