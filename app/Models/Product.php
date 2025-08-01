<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Enums\ProductStatusEnum;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->fit('crop', 100, 100);

        $this->addMediaConversion('preview')
            ->width(480);
        $this->addMediaConversion('large')
            ->width(1200);
    }
    protected $fillable = [
        'title',
        'slug',
        'description',
        'department_id',
        'category_id',
        'price',
        'status',
        'quantity',
        'created_by',
        'updated_by'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function scopeForVendor(Builder $query) : Builder 
    {
        return $query -> where('created_by', Auth::user()->id);    
    }

    public function scopePublished(Builder $query) : Builder
    {
        return $query->where('status',ProductStatusEnum::Published);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variationTypes() : HasMany 
    {
        return $this -> hasMany(VariationType::class);    
    }

    public function variations() : HasMany 
    {
        return $this->hasMany(ProductVariation::class,'product_id');    
    }
}
