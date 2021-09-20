<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'post_excerpt',
        'post',
        'jsonData',
        'slug',
        'user_id',
        'featuredImage',
        'metaDescription',
        'views'
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['slug'] = $this->createUnique($title);
        $this->attributes['title'] = $title;
    }
    public function createUnique ($title){
        $slug = Str::slug($title , '-');

        $count = Blog::where('slug' , 'LIKE' ,"{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : $count;
        return $newCount = 0 ? "$slug" : "$slug-$newCount";
    }

    public function tag()
    {
       return $this->belongsToMany('App\Models\Tag','blogtags');
    }

    public function cat()
    {
        return $this->belongsToMany('App\Models\Category','blogcategories');
    }
}
