<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use App\Models\Module;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'isQualified' => $user->isQualified(),
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

    public function updateProfile(Request $request)
    {
        $photo = $request->photo;
        $user = ModelsUser::find(Auth::id());

        if (!$photo) {
            return response(['message' => "No photo was uploaded!"], 404);
        }

        // Check already has a photo before
        $old = 'uploads' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $user->image;

        if ($user->image && Storage::exists($old)) {
            $user->deleteProfile();
        }

        $new = $user->createFromBase64($photo);
        $user->image = $new;
        $user->save();
    }

    public function unreadCount()
    {
        return auth()->user()->announcements()->wherePivotNull('read_at')->count();
    }
}
