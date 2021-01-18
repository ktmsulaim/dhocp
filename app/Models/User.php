<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'api_token',
        'batch_id',
        'name',
        'enroll_no',
        'dob_password',
        'dob',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'dob_password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->dob_password;
    }

    public function setDobPasswordAttribute($value)
    {
        $this->attributes['dob_password'] = bcrypt($value);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->using(ItemUser::class)
            ->withPivot(['id', 'value', 'value_info', 'is_valid', 'valid_message', 'created_at', 'updated_at']);
    }

    public function itemGroups()
    {
        return $this->hasMany(ItemGroup::class);
    }

    public function getItemsByModule($module_id)
    {
        return $this->items()->where('module_id', $module_id)->orderBy('order', 'asc')->get();
    }

    /**
     * @var ItemUser $item
     * @return Boolean
     */
    public function hasItem($item, $item_group = null)
    {
        if ($item_group) {
            $item = $this->items()->where(['item_id' => $item->id, 'item_group_id' => $item_group->id])->exists();
        } else {
            $item = $this->items()->where(['item_id' => $item->id])->exists();
        }
        return $item;
    }


    public function attendedItems($module_id)
    {
        return $this->items()->where('module_id', $module_id)->whereNotIn('value', ['']);
    }

    /**
     * @return int
     */
    public function totalAttended($module_id)
    {
        $module = Module::find($module_id);

        if ($module && $module->isRepeatable()) {
            return $module->itemGroups()->count();
        } elseif ($this->items) {
            return $this->attendedItems($module_id)->count();
        } else {
            return 0;
        }
    }

    /**
     * @return int
     */

    public function validItems($module_id, $validStatus)
    {
        $module = Module::findOrFail($module_id);

        if ($module->isRepeatable()) {
            if ($this->itemGroups()->where('module_id', $module_id)->exists()) {
                switch ($validStatus) {
                    case 0:
                        return $this->itemGroups()->where(['module_id' => $module_id, 'is_valid' => $validStatus])->count();
                        break;

                    case 1:
                        return $this->itemGroups()->where(['module_id' => $module_id, 'is_valid' => $validStatus])->count();
                        break;

                    case 2:
                        return $this->itemGroups()->where(['module_id' => $module_id, 'is_valid' => $validStatus])->count();
                        break;

                    default:
                        return 0;
                        break;
                }
            } else {
                return 0;
            }
        } else {
            if ($this->items) {
                switch ($validStatus) {
                    case 0:
                        return $this->attendedItems($module_id)->where('is_valid', 0)->count();
                        break;

                    case 1:
                        return $this->attendedItems($module_id)->where('is_valid', 1)->count();
                        break;

                    case 2:
                        return $this->attendedItems($module_id)->where('is_valid', 2)->count();
                        break;

                    default:
                        return 0;
                        break;
                }
            } else {
                return 0;
            }
        }
    }
}
