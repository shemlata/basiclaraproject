<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicianController;
use App\Http\Controllers\PatientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [ClinicianController::class, 'index'])->name('home');

// clinitian module start 
Route::POST('/clinician-patient/data', [ClinicianController::class, 'getClinicianPatientData'])->name('clinician.patient.data');
Route::POST('/submit-form-handler', [ClinicianController::class, 'submitForm'])->name('submit.form.handler');
Route::post('/appointment/store', [ClinicianController::class, 'storeSelectedClinician'])->name('store.selected.clinician');
Route::post('/getAvailability', [ClinicianController::class, 'getAvailability'])->name('clinician.getAvailability');

// patient module start 
Route::get('/show-appointment', [PatientController::class, 'showAppointment'])->name('show.appointment');
Route::post('/update-status', [PatientController::class, 'updateStatus'])->name('update.availability.status');
Route::post('/check-appointment-status', [PatientController::class, 'checkAppointmentStatus'])->name('check.appointment.status');
Route::post('/check-all-patient-appointment-status', [PatientController::class, 'checkAllPatientAppointmentStatus'])->name('check.all.patient.appointment.status');
Route::post('/update-all-patient-appointment-status', [PatientController::class, 'updateAllPatientAppointmentStatus'])->name('update.all.patient.appointment.status');
