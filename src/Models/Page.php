<?php

namespace Startupful\WebpageManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use VanOns\Laraberg\Traits\RendersContent;

class Page extends Model
{
    use SoftDeletes, RendersContent;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'type',
        'parent_id',
        'is_published',
        'published_at',
        'meta_data',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'meta_data' => 'string',
        'content' => 'string',
    ];

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function getContentAttribute($value)
    {
        // If content is stored as JSON, decode it
        if (is_string($value) && is_array(json_decode($value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
            return json_encode(json_decode($value, true));
        }
        return $value;
    }

    public function render_content()
    {
        if (method_exists($this, 'renderContent')) {
            return $this->renderContent();
        }
        
        // Laraberg의 renderContent 메서드가 없는 경우, 기본 content를 반환
        return $this->content;
    }
}