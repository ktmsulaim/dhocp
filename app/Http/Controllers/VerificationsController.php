<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserVerification as ResourcesUserVerification;
use App\Http\Resources\Verification as ResourcesVerification;
use App\Models\User;
use App\Models\UserVerification;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VerificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $verifications = Verification::all();
        return Inertia::render('admin/verifications/Index', [
            'verifications' => ResourcesVerification::collection($verifications),
        ]);
    }

    public function allVerificationsApi()
    {
        return Verification::enabled()->get();
    }

    public function getByStudentApi(User $user)
    {
        $verifications = UserVerification::where('user_id', $user->id)->get();

        return [
            'verifications' => Verification::enabled()->get(),
            'status' => ResourcesUserVerification::collection($verifications),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
            'order' => 'required',
        ]);

        Verification::create($request->all());
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function show(Verification $verification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function edit(Verification $verification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Verification $verification)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
            'order' => 'required',
        ]);

        $verification->update($request->all());
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Verification  $verification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Verification $verification)
    {
        $verification->delete();
        return Redirect::back();
    }

    public function updateOrder(Request $request)
    {
        $verifications = $request->verifications;

        if ($verifications && count($verifications) > 0) {
            foreach ($verifications as $key => $item) {
                $verification = Verification::findOrFail($item['id']);
                $verification->order = $item['order'];
                $verification->save();
            }
        }

        return Verification::all();
    }

    private function attachVerifications(array $student_ids, int $verification_id)
    {
        if (count($student_ids) > 0) {
            foreach ($student_ids as $key => $student_id) {
                $student = User::findOrFail($student_id);

                if (!$student->hasVerification($verification_id)) {
                    $student->verifications()->attach($verification_id);
                }
            }
        }
    }

    public function approve(Request $request, Verification $verification)
    {
        $student_ids = $request->students;

        if (is_array($student_ids)) {
            $this->attachVerifications($student_ids, $verification->id);

            foreach ($student_ids as $key => $student_id) {
                $uv = UserVerification::where(['user_id' => $student_id, 'verification_id' => $verification->id])->first();
                $uv->status = 1;
                $uv->save();
            }
        } else {
            return response(['message' => 'No students selected!', 404]);
        }
    }

    public function disapprove(Request $request, Verification $verification)
    {
        $student_ids = $request->students;

        if (is_array($student_ids)) {
            $this->attachVerifications($student_ids, $verification->id);

            foreach ($student_ids as $key => $student_id) {
                $uv = UserVerification::where(['user_id' => $student_id, 'verification_id' => $verification->id])->first();
                $uv->status = 0;
                $uv->save();
            }
        } else {
            return response(['message' => 'No students selected!', 404]);
        }
    }

    public function studentApprove(Request $request, $id, Verification $verification)
    {
        $student = User::findOrFail($id);

        if (!$student->hasVerification($verification->id)) {
            $student->verifications()->attach($verification->id);
        }

        $userVerification = UserVerification::where(['user_id' => $id, 'verification_id' => $verification->id])->first();
        $userVerification->status = 1;
        $userVerification->save();

        return new ResourcesUserVerification($userVerification);
    }

    public function studentDisapprove(Request $request, $id, Verification $verification)
    {
        $student = User::findOrFail($id);

        if (!$student->hasVerification($verification->id)) {
            $student->verifications()->attach($verification->id);
        }

        $userVerification = UserVerification::where(['user_id' => $id, 'verification_id' => $verification->id])->first();
        $userVerification->status = 0;
        $userVerification->save();

        return new ResourcesUserVerification($userVerification);
    }

    public function updateRemarks(Request $request, $id, Verification $verification)
    {
        $student = User::findOrFail($id);

        if (!$student->hasVerification($verification->id)) {
            $student->verifications()->attach($verification->id);
        }

        $remarks = $request->remarks;
        $userVerification = UserVerification::where(['user_id' => $id, 'verification_id' => $verification->id])->first();
        $userVerification->remarks = $remarks;
        $userVerification->save();
    }
}
