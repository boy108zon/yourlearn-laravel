<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'menus';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name',
        'title',
        'url',
        'slug',
        'sequence',
        'status',
        'parent_id',
        'icon'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
    
   
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
