<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules'; // Adjust if your table name is different

    protected $fillable = ['title', 'description']; // Adjust based on your columns

    // Relationship with materials
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
