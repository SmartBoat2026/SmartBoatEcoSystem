<?php
// FILE: app/Models/Admin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table      = 'admin';
    protected $primaryKey = 'admin_id';
    public    $timestamps = false;

    protected $fillable = [
        'username', 'password', 'role', 'name',
    ];

    /**
     * Plain-text password comparison (your DB stores passwords as plain text).
     * If you ever migrate to bcrypt, change this to: Hash::check($password, $this->password)
     */
    public function verifyPassword(string $password): bool
    {
        return $this->password === $password;
    }
}
