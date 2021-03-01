<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        'image',
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
            ->withPivot(['id', 'value', 'value_info', 'is_valid', 'valid_message', 'admin_updated', 'created_at', 'updated_at']);
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

    public function invalidAlert()
    {
        if ($this->items()->exists()) {
            $items = [];
            $modules = [];

            foreach ($this->items as $key => $item) {
                if ($item->pivot->is_valid == 0) {
                    $module = $item->module;

                    array_push($items, $item);

                    if (!in_array($module, $modules)) {
                        array_push($modules, $module);
                    }
                }
            }

            return [
                'items' => count($items),
                'modules' => count($modules),
            ];
        }

        return [
            'items' => 0,
            'modules' => 0,
        ];
    }

    public function verifications()
    {
        return $this->belongsToMany(Verification::class, 'user_verifications')
            ->using(UserVerification::class)
            ->withPivot(['id', 'status', 'remarks', 'created_at', 'updated_at']);
    }

    public function hasVerification($verification_id)
    {
        return $this->verifications()->where('user_verifications.verification_id', $verification_id)->exists();
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_users')
            ->using(AnnouncementUser::class)
            ->withPivot(['id', 'user_id', 'announcement_id', 'read_at']);
    }

    public function profile()
    {
        if ($this->image) {
            $img = 'uploads' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $this->image;
            if (Storage::exists($img)) {
                return asset('storage' . DIRECTORY_SEPARATOR . $img);
            } else {
                return asset('img/user.png');
            }
        } else {
            return asset('img/user.png');
        }
    }

    public function createFromBase64($filename, $dir = null)
    {
        $ds = DIRECTORY_SEPARATOR;
        $image_64 = $filename; //your base64 encoded data

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = time() . '.' . $extension;

        if (!File::exists(public_path('uploads' . $ds . 'profile'))) {
            Storage::makeDirectory(public_path('uploads' . $ds . 'profile'), 0777, true, true);
        }

        if (!$dir) {
            $dir = 'uploads' . $ds . 'profile' . $ds;
        }

        Storage::put($dir . $imageName, base64_decode($image));

        return $imageName;
    }

    public function deleteProfile($dir = null)
    {
        $ds = DIRECTORY_SEPARATOR;

        if (empty($dir)) {
            $dir = 'uploads' . $ds . 'profile' . $ds;
        }

        if (Storage::exists($dir . $this->image)) {
            Storage::delete($dir . $this->image);
        }
    }

    public function getItem($item_id)
    {
        $itemUser = ItemUser::where(['user_id' => $this->id, 'item_id' => $item_id])->first();

        if ($itemUser) {
            return $itemUser->getValue();
        }
    }

    public function getItemByKey($key)
    {
        if ($key) {
            $item = Item::where('key', $key)->first();

            if ($item) {
                return $this->getItem($item->id);
            }
        }
    }

    public function isQualified()
    {
        // s
        $final = $this->verifications()->where('name', 'Final Verification')->first();

        if ($final && $final->pivot->status === 1) {
            return true;
        }

        return false;
    }

    public function checkHasAllVerifications()
    {
        $verifications = Verification::enabled()->get();

        if($verifications && count($verifications) > 0) {
            foreach($verifications as $verification) {
                if(!$this->verifications()->where('verifications.id', $verification->id)->exists()) {
                    $this->verifications()->attach($verification->id);
                }
            }
        }
    }
}
