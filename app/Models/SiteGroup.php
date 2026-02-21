<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'netbox_id',
        'name',
        'slug',
        'parent_id',
        'parent_name',
        'description',
        'depth',
        'site_count',
    ];

    /** Site Group induk (self-referencing) */
    public function parent()
    {
        return $this->belongsTo(SiteGroup::class, 'parent_id');
    }

    /** Children site groups */
    public function children()
    {
        return $this->hasMany(SiteGroup::class, 'parent_id');
    }

    /** Sites yang masuk di site group ini */
    public function sites()
    {
        return $this->hasMany(Site::class, 'site_group_id');
    }
}
