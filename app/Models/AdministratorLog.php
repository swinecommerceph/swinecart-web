<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;


class AdministratorLog extends Model
{
    protected $table = 'administrator_logs';
    protected $fillable = ['admin_id', 'admin_name', 'user', 'category', 'action'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
