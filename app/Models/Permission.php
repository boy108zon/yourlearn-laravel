<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name','slug'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
   
    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }
}
