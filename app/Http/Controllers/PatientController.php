<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Availability;

class PatientController extends Controller
{
    //
    public function showAppointment(Request $request)
    {

        $data = Availability::select('id', 'clinitian_id', 'patient_id', 'title', 'availability_start', 'availability_end', 'availability_background_color', 'availability_border_color', 'appointment_status')->where('appointment_status', '=', NULL)->get();


        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateStatus(Request $request)
    {
        
        $availability = Availability::where('id', '=', $request->id)->where('clinitian_id', '=', $request->clinitian_id)->first();


        if (!$availability) {
            return response()->json([
                'success' => false,
                'message' => 'Availability record not found.'
            ], 404);
        }

        $availability->patient_id = $request->input('patient_id');
        $availability->appointment_status = $request->input('status');
        $availability->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'availability' => $availability
        ]);
    }

    public function checkAppointmentStatus(Request $request)
    {
        $data = Availability::select('clinitian_id', 'patient_id', 'title', 'availability_start', 'availability_end', 'availability_background_color', 'availability_border_color', 'appointment_status')->whereIn('appointment_status', ['pending', 'confirmed', 'completed'])->where('patient_id', '=', $request->patient_id)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function checkAllPatientAppointmentStatus(Request $request)
    {
        $data = Availability::select('id', 'clinitian_id', 'patient_id', 'title', 'availability_start', 'availability_end', 'availability_background_color', 'availability_border_color', 'appointment_status')
            ->where('clinitian_id', $request->clinitian_id)
            ->where(function ($query) {
                $query->where('appointment_status', '!=', 'cancelled')
                    ->orWhereNull('appointment_status');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateAllPatientAppointmentStatus(Request $request)
    {
        $availability = Availability::where('id', '=', $request->id)->where('clinitian_id', '=', $request->clinitian_id)->first();


        if (!$availability) {
            return response()->json([
                'success' => false,
                'message' => 'Availability record not found.'
            ], 404);
        }

        $availability->appointment_status = $request->input('status');
        $availability->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'availability' => $availability
        ]);
    }
}
