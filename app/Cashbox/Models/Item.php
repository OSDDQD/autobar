<?php

namespace App\Cashbox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Intervention\Image\ImageManagerStatic;
use App\Cashbox\Traits\HasTranslations;

class Item extends Model
{
    use HasFactory;
    use CrudTrait;
    use HasTranslations;

    // Set options for image attributes
    const DISK = 'public';
    const FIELD = 'image';
    const PATH = 'items';
    const EXT = 'png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'position',
        'category_id',
        'price',
        'is_active',
        'option_name'
    ];

    protected $appends = [
        'stock',
        'sold',
        'profit'
    ];

    protected $translatable = ['name', 'description', 'option_name'];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            \Storage::disk(self::DISK)->delete($obj->{self::FIELD});
        });
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the category for the item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the orders for the item.
     */
    public function orders()
    {
        return $this->hasMany(OrderItem::class, 'item_id', 'id');
    }

    /**
     * Get the options for the item.
     */
    public function options()
    {
        return $this->hasMany(Option::class, 'item_id', 'id');
    }

    /**
     * Get the incomes for the item.
     */
    public function incomes()
    {
        return $this->hasMany(Income::class, 'item_id', 'id');
    }

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }

    public function getStockAttribute()
    {
        return $this->incomes->sum('quantity') - $this->orders->sum('quantity');
    }

    public function getSoldAttribute()
    {
        return $this->orders->sum('quantity');
    }

    public function getProfitAttribute()
    {
        return $this->orders->sum('total') ;
    }

//    public function stock()
//    {
//        return $this->incomes->sum('quantity') - $this->orders->sum('quantity');
//    }

    public function getIncomesOption($option_id)
    {
        return $this->incomes->where('option_id', $option_id)->sum('quantity');
    }

    public function getSoldOption($option_id)
    {
        return $this->orders->where('option_id', $option_id)->sum('quantity');
    }

    public function getStockOption($option_id)
    {
        return $this->getIncomesOption($option_id) - $this->getSoldOption($option_id);
    }

    public function getOption($option_id)
    {
        return $this->options->where('id', $option_id)->first();
    }

    /**
     * Store image attribute
     */
    public function setImageAttribute($value)
    {
        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk(self::DISK)->delete($this->{self::FIELD});

            // set null in the database column
            $this->attributes[self::FIELD] = null;
        }

        // if a base64 was sent, store it in the db
        if (\Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = ImageManagerStatic::make($value)->encode(self::EXT, 90);

            // 1. Generate a filename.
            $filename = md5($value . time()) . '.' . self::EXT;

            // 2. Store the image on disk.
            \Storage::disk(self::DISK)->put(self::PATH . '/' . $filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            \Storage::disk(self::DISK)->delete($this->{self::FIELD});

            // 4. Save the public path to the database
            $this->attributes[self::FIELD] = self::PATH . '/' . $filename;
        }
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? \Storage::disk(self::DISK)->url($this->image)
            : false;
    }
}
