<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use App\Models\Module;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppController extends Controller
{
    public function index()
    {
        $data = [];
        $user = auth()->user();

        $modules = Module::active()->get();

        if ($modules && count($modules) > 0) {
            foreach ($modules as $key => $module) {
                if ($module->isRepeatable()) {
                    array_push($data, [
                        'module' => $module,
                        'hasInvalid' => $module->hasInvalid($user->id),
                        'total_items' => $user->itemGroups()->where('module_id', $module->id)->count(),
                        'total_pending' => $user->validItems($module->id, 1),
                        'total_valid' => $user->validItems($module->id, 2),
                        'total_invalid' => $user->validItems($module->id, 0),
                    ]);
                } else {
                    array_push($data, [
                        'module' => $module,
                        'hasInvalid' => $module->hasInvalid($user->id),
                        'total_items' => count($module->items),
                        'total_attended' => $user->totalAttended($module->id),
                        'total_attended_perc' => $this->getPercentage(count($module->items), $user->validItems($module->id, 2)),
                        'total_pending' => $user->validItems($module->id, 1),
                        'total_valid' => $user->validItems($module->id, 2),
                        'total_invalid' => $user->validItems($module->id, 0),
                    ]);
                }
            }
        }

        return Inertia::render('user/Index', [
            'user' => $user,
            'modules' => $data,
            'invalidAlert' => $user->invalidAlert(),
        ]);
    }


    private function getPercentage($totalItems, $validItems)
    {
        if ($totalItems && $totalItems > 0) {
            $perc = ($validItems * 100) / $totalItems;
            $perc = number_format($perc, 2);

            return floatval($perc);
        } else {
            return 0;
        }
    }


    public function profile()
    {
        $user = new User(auth()->user());
        return Inertia::render('user/Profile', [
            'user' => $user,
        ]);
    }
}
