<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemUser extends Pivot
{
    use HasFactory;

    protected $table = 'item_user';

    protected $dates = ['admin_updated', 'created_at', 'updated_at'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getValue()
    {
        if ($this->value) {
            $type = $this->item->type;

            if ($type == 'dropdown') {
                $options = json_decode($this->item->additional, true);
                $key = array_search($this->value, array_column($options, 'value'));

                return $options[$key]['key'];
            } elseif ($type == 'checkbox') {
                if ($this->value == 1) {
                    return 'Yes';
                } elseif ($this->value == 0) {
                    return 'No';
                }
            } else {
                return $this->value;
            }
        }
    }
}
