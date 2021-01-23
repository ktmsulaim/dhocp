<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

// use setasign\Fpdi\Fpdi;

class PrintFormController extends Controller
{
    public function printUser()
    {
        $user = User::find(Auth::id());

        if (!$user || !$user->isQualified()) {
            return Redirect::back();
        }

        $this->print($user);
        return Redirect::route('user.progress');
    }

    public function printAdmin(Request $request)
    {
        $user_id = $request->user_id;

        $user = User::find($user_id);

        if (!$user_id || !$user) {
            return Redirect::bak();
        }

        $this->print($user);
    }

    private function print($user)
    {
        // initiate FPDI
        $pdf = new \setasign\Fpdi\Fpdi('P', 'mm', 'A4');
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile(public_path('docs' . DIRECTORY_SEPARATOR . 'form.pdf'));
        // import page 1
        $tplId = $pdf->importPage(1);
        // use the imported page and place it at point 10,10 with a width of 100 mm
        $pdf->useTemplate($tplId, 5, 5, 200);

        // second step
        $pdf->SetFont('Helvetica');
        $pdf->setFontSize(12.5);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(167, 19.5);
        $pdf->Write(0, $user->enroll_no);

        $pdf->setFontSize(11);

        $amount = $user->getItemByKey('convocation-donation');
        if ($amount) {
            $pdf->SetXY(23, 85);
            $pdf->Write(0, $amount);
        }

        $receipt = $user->getItemByKey('convocation-receipt-no');
        if ($receipt) {
            $pdf->SetXY(63, 85);
            $pdf->Write(0, $receipt);
        }

        $convocation = $user->items()->where('key', 'convocation-donation')->first();
        $updated = Carbon::parse($convocation->pivot->admin_updated)->format('d-m-Y');
        if ($updated) {
            $pdf->SetXY(101.3, 85);
            $pdf->Write(0, $updated);
        }


        //insert image
        $image = asset($user->profile());

        if ($image != asset('img/user.png')) {
            $pdf->Image($image, 158.2, 64.5, 35, 43);
        }


        $pdf->setFontSize(11);

        $name = $user->getItemByKey('name-of-applicant');
        if ($name) {
            $pdf->SetXY(110, 126.1);
            $pdf->Write(0, $name);
        }

        $dob = $user->getItemByKey('date-of-birth');
        if ($dob) {
            $pdf->SetXY(110, 134.8);
            $pdf->Write(0, $dob);
        }

        $rc = $user->getItemByKey('religion-and-caste');
        $category = $user->getItemByKey('category');
        $rc_cat = $rc . ', ' . $category;
        if ($rc_cat) {
            $pdf->SetXY(110, 144.18);
            $pdf->Write(0, $rc_cat);
        }

        $name_of_father = $user->getItemByKey('name-of-father');
        if ($name_of_father) {
            $pdf->SetXY(110, 153);
            $pdf->Write(0, $name_of_father);
        }

        $address = $user->getItemByKey('permanent-address');
        if ($address) {
            $pdf->SetXY(110, 159.4);
            $pdf->MultiCell(90, 5, $address);
        }

        $district = $user->getItemByKey('district');
        $state = $user->getItemByKey('state');
        $ds = $district . ', ' . $state;

        if ($ds) {
            $pdf->SetXY(110, 179.7);
            $pdf->Write(0, $ds);
        }

        $phone = $user->getItemByKey('mobile-no');
        $email = $user->getItemByKey('email');
        $pe = $phone . ', ' . $email;

        if ($pe) {
            $pdf->SetXY(110, 188.5);
            $pdf->Write(0, $pe);
        }

        // getting applied documents
        $items = $user->items()->where('module_id', 5)->wherePivot('value', 1)->get();
        $names = $items->pluck('label')->join(', ');

        if ($names) {
            $pdf->SetXY(110, 195);
            $pdf->MultiCell(90, 5, $names);
        }

        $pdf->setFontSize(8.5);

        $name = $user->getItemByKey('name-of-applicant');
        if ($name) {
            $pdf->SetXY(25, 219.1);
            $pdf->Write(0, $name);
        }

        $pdf->setFontSize(6);

        $pdf->SetXY(176, 262.5);
        $pdf->Write(0, Carbon::now()->format('d-m-Y g:i A'));

        $pdf->Output("D", $user->enroll_no . '.pdf');
    }
}
