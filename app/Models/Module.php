<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOffice($query)
    {
        return $query->where('office_use', 1);
    }

    public function scopePublic($query)
    {
        return $query->where('office_use', 0);
    }

    public function itemGroups()
    {
        return $this->hasMany(ItemGroup::class);
    }

    public function isRepeatable()
    {
        return $this->repeatable == 1;
    }

    public function itemUsers()
    {
        return $this->hasManyThrough(ItemUser::class, Item::class);
    }

    public function orderedItemUsers()
    {
        return $this->itemUsers()->orderBy('order')->get();
    }

    public function hasInvalid($user_id)
    {
        return $this->itemUsers()->where(['user_id' => $user_id, 'is_valid' => 0])->count() > 0;
    }

    public function hasDownloadableFiles($user_id)
    {
        $items = $this->itemUsers()->with('item')->where('user_id', $user_id)->get();
        $files = [];

        if ($items && count($items) > 0) {
            foreach ($items as $key => $item) {
                if ($item->item->type == 'file' && !empty($item->value_info)) {
                    $file_info = json_decode($item->value_info, true);
                    $file = 'uploads' . DIRECTORY_SEPARATOR . $file_info['name'];
                    if (Storage::exists($file)) {
                        array_push($files, $file);
                    }
                }
            }
        }

        return count($files) > 0;
    }

    public function getDownloadableFiles($user_id)
    {
        if ($this->hasDownloadableFiles($user_id)) {
            $items = $this->itemUsers()->with('item')->where('user_id', $user_id)->get();
            $files = [];

            if ($items && count($items) > 0) {
                foreach ($items as $key => $item) {
                    if ($item->item->type == 'file' && !empty($item->value_info)) {
                        $file_info = json_decode($item->value_info, true);
                        $file = 'uploads' . DIRECTORY_SEPARATOR . $file_info['name'];
                        if (Storage::exists($file)) {
                            array_push($files, ['file' => public_path('storage' . DIRECTORY_SEPARATOR . $file), 'newName' => $item->item->label . '.' . $file_info['ext']]);
                        }
                    }
                }
            }

            return $files;
        }
    }
}
