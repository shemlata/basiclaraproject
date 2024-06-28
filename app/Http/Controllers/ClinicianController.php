<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Availability;

class ClinicianController extends Controller
{
    public function index()
    {
        $clinicians = User::all();

        return view('home', compact('clinicians'));
    }

    public function getClinicianPatientData(Request $request)
    {
        if ($request->role == 'clinitian') {
            $dropdownData = User::Where('role', '=', 'clinitian')->get();
            $dropdownHtml = ' <div class="inline-form"><select class="form-control close_drop_down" id="clinicianDropdown" data-custom-value="' . $request->role . '">';
        }

        if ($request->role == 'patient') {
            $dropdownData = User::Where('role', '=', 'patient')->get();
            $dropdownHtml = ' <div class="inline-form"><select class="form-control close_drop_down" id="patientDropdown" data-custom-value="' . $request->role . '">';
        }

        $dropdownHtml .= '<option value="">Select By Email</option>';

        foreach ($dropdownData as $item) {
            $dropdownHtml .= '<option value="' . $item->id . '" >' . $item->email . '</option>';
        }

        $dropdownHtml .= '</select>';

        $dropdownHtml .= '<button id="registerButton" class="btn btn-success btn-sm ml-2">Not Register?</button></div>';

        $dropdownHtml .=  '<div id="registerFormDiv" style="display: none;">
        <form action="' . route('submit.form.handler') . '" method="POST" id="registerForm">
        ' . csrf_field() . '
            <h6 class="text-center mt-3">register as ' . $request->role . '</h6>
            <div class="alert alert-danger mt-2" id="show-error-msg" style="display: none;"></div>
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Please enter first name" value="" required>
            </div>

            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Please enter last name" value="" required>
            </div>

            <div class="form-group">
                <label for="number">Phone Number:</label>
                <input type="tel" value="" class="form-control" id="number" name="number" placeholder="Please enter number" required>
            </div>

             <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" value="" class="form-control" id="email" name="email" placeholder="Please enter email" required>
            </div>

            <input type="hidden" class="form-control" id="role" name="role" value="' . $request->role . '" >

            <button type="submit" class="btn btn-primary mt-1">Register</button>';


        $dropdownHtml .=  '</form></div>';

        $response = [
            'html' => $dropdownHtml,
        ];
        

        return response()->json($response);
    }

    public function submitForm(Request $request)
    {
        
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'number' => [
                'required',
                'string',
                'size:10',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('number', $request->number);
                }),
            ], 
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('email', $request->email);
                }),
            ],
            'role' => 'required',
        ]);

        $user = new User();
        $user->full_name = $request->firstname . ' ' . $request->lastname;
        $user->number = $request->number;
        $user->email = $request->email;
        $user->role = $request->role;

        
        $user->save();

        return response()->json(['message' => 'Form submitted successfully. Please select your name from dropdown and do further process.']);
    }

    public function storeSelectedClinician(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'availability_start' => 'required|date',
            'availability_end' => 'required|date|after:availability_start',
        ]);

        
        $appointment = new Availability();
        if ($request->role_name == 'clinitian') {
            $appointment->clinitian_id  = $request->input('existing_clinician_id');
        }

        if ($request->role_name == 'patient') {
            $appointment->patient_id  = $request->input('existing_clinician_id');
        }

        $appointment->title = $request->input('title');
        $appointment->availability_start = $request->input('availability_start');
        $appointment->availability_end = $request->input('availability_end');
        $appointment->appointment_status = NULL;
        $appointment->save();

        return response()->json(['success' => true, 'message' => 'Availability created successfully!', 'role_name' => $request->role_name, 'existing_id' => $request->input('existing_clinician_id')]);
    }

    public function getAvailability(Request $request)
    {
        
        if ($request->role_name == 'clinitian') {
            $availabilityData = Availability::select('id', 'clinitian_id', 'patient_id', 'title', 'availability_start', 'availability_end', 'availability_background_color', 'availability_border_color', 'appointment_status')
                ->where(function ($query) {
                    $query->whereIn('appointment_status', ['pending'])
                        ->orWhereNull('appointment_status');
                })
                ->where('clinitian_id', '=', $request->existing_id)->get();
        }

        if ($request->role_name == 'patient') {
            $availabilityData = Availability::select('id', 'clinitian_id', 'patient_id', 'title', 'availability_start', 'availability_end', 'availability_background_color', 'availability_border_color', 'appointment_status')
                ->where(function ($query) {
                    $query->whereIn('appointment_status', ['pending'])
                        ->orWhereNull('appointment_status');
                })
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $availabilityData
        ]);
    }
}
