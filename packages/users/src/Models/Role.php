<?php

namespace User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'title'
    ];
    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }
}
