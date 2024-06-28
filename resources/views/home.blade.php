<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
   
    <style>
        .form-container {
            display: none;
        }

        .inline-form {
            display: flex;
            align-items: center;
        }

        .small-dropdown {
            font-size: 12px;
            padding: 0px;
            height: 24px;
        }
    </style>
</head>

<body>

    <div class="container w-70 h-10 mt-2">
        <h6 class="mt-2">Select Your Role</h6>

        <button id="openFormPatientButton" class="btn btn-primary btn-sm">I am a Patient</button>
        <button id="openFormsClinitianButton" class="btn btn-success btn-sm">I am a Clinician</button>

        <input type="hidden" id="role_name_hidden" name="role_name_hidden" value="">
        <input type="hidden" id="existing_id_hidden" name="existing_id_hidden" value="">


        <div id="patientFormDiv" style="display: none;">
            <h6 class="mt-2">Patient Module</h6>
        </div>

        <div id="clinicianFormDiv" style="display: none;">
            <h6 class="mt-2">Clinician Module</h6>
        </div>

        <div class="mt-2" id="getDropdownDiv"></div>

        <div class="mt-2" id="allButtonDiv">
            <button id="showAvailability" class="btn btn-info btn-sm" style="display: none;">showAvailability</button>

            <button id="createAvailability" class="btn btn-info btn-sm" style="display: none;">createAvailability</button>
            <button id="bookDoctorAppointment" class="btn btn-info btn-sm" style="display: none;">bookDoctorAppointment</button>
            <button id="viewAllPatientAppointmentAction" class="btn btn-info btn-sm" style="display: none;">viewAllPatientAppointmentAction</button>
            <button id="checkYourAppointmentStatus" class="btn btn-info btn-sm" style="display: none;">checkYourAppointmentStatus</button>
        </div>

        <div class="mt-2" id="createAvailabilityDiv" style="display: none;">
            <div class="alert alert-danger" id="registerAvailabilitySlotFormMessages" style="display: none;"></div>
            <form id="selectClinicianForm" action="" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="availability_start">Availability Start</label>
                    <input type="datetime-local" class="form-control" id="availability_start" name="availability_start" required>
                </div>
                <div class="form-group">
                    <label for="availability_end">Availability End</label>
                    <input type="datetime-local" class="form-control" id="availability_end" name="availability_end" required>
                </div>

                <input type="hidden" id="role_name" name="role_name" value="">
                <input type="hidden" id="existing_clinician_id" name="existing_clinician_id" value="">

                <button type="submit" id="submitAvailabilitySlot" class="btn btn-primary mb-4">Submit</button>
            </form>

        </div>

        <div id="calendar" class="w-70 h-70 mb-3 mt-2" style="display: none;"></div>

        <div class="mt-2" id="bookDoctorAppointmentDiv" style="display: none;">
            <div class="alert alert-danger" id="registerbookDoctorAppointmentFormMessages" style="display: none;">
                <table class="table table-striped" id="oldtable">
                    <thead>
                        <tr>
                            <th>Clinician ID</th>
                            <th>Title</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="availabilityTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-2" id="checkYourAppointmentStatusDiv" style="display: none;">
            <div class="alert alert-danger" id="checkYourAppointmentStatusDivMessages" style="display: none;">
                <table class="table table-striped" id="newtable">
                    <thead>
                        <tr>
                            <th>Clinician ID</th>
                            <th>Title</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="appStatusdiv">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-2" id="viewAllPatientAppointmentDiv" style="display: none;">
            <div class="alert alert-danger" id="viewAllPatientAppointmentDivMessages" style="display: none;">
                <table class="table table-striped" id="newtable">
                    <thead>
                        <tr>
                            <th>Clinician ID</th>
                            <th>Title</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="patientAppStatusdiv">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.14/index.global.js" integrity="sha512-pUg0N+xOuIMd7eRXDFUItwJxKJgSQJHTqXFqC01bQmU/93RH5PU2QYDhpkSmZCodoGA9cisFVna7OELJg0a3/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.14/index.global.min.js" integrity="sha512-JEbmnyttAbEkbkpvW1vRqBzY3Otrp0DFwux9+JQ6kXe2mQfUmBpImuREMZS0advTaaCMotaYB5gIng/uPw3r6w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            $('#openFormPatientButton').click(function() {
                $('#registerFormDiv').hide();
                getDataFun('patient');
                $('#patientFormDiv').slideDown();
                $('#clinicianFormDiv').hide();
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $("#viewAllPatientAppointmentAction").hide();
                $('#bookDoctorAppointmentDiv').hide();
                $('#checkYourAppointmentStatusDiv').hide();
                $('#allButtonDiv').hide();
                $("#role_name_hidden").val('patient');
                $("#existing_id_hidden").val('');
            });

            $('#openFormsClinitianButton').click(function() {
                $('#registerFormDiv').hide();
                getDataFun('clinitian');
                $('#clinicianFormDiv').slideDown();
                $('#patientFormDiv').hide();
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $("#viewAllPatientAppointmentAction").hide();
                $('#bookDoctorAppointmentDiv').hide();
                $('#checkYourAppointmentStatusDiv').hide();
                $('#allButtonDiv').hide();
                $("#role_name_hidden").val('clinitian');
                $("#existing_id_hidden").val('');
            });

            function getDataFun(role) {
                $("#show-error-msg").hide();
                
                $.ajax({
                    url: "{{ route('clinician.patient.data') }}", 
                    data: {
                        _token: "{{ csrf_token() }}",
                        role
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {

                        
                        $('#getDropdownDiv').html(response.html);
                        $('#registerButton').click(function() {

                            $('#registerFormDiv').slideDown();
                            $('#registerForm').submit(function(e) {
                                e.preventDefault(); 
                                var formData = $(this).serialize(); 

                                
                                $.ajax({
                                    url: "{{route('submit.form.handler')}}", 
                                    type: 'POST',
                                    data: formData, 
                                    success: function(response) {
                                        
                                        $("#show-error-msg").show();
                                        $("#show-error-msg").html(response.message);


                                    },
                                    error: function(xhr, status, error) {
                                       
                                        $("#show-error-msg").show();
                                        $("#show-error-msg").html(xhr.responseJSON.message);
                                    }
                                });
                            });
                        });
                    },
                    error: function(xhr) {
                        alert('Error fetching clinician data');
                        console.error(xhr);
                    }
                });
            }

            $(document).on('change', '.close_drop_down', function() {
                var role = $(this).data('custom-value');
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $('#bookDoctorAppointmentDiv').hide();
                $('#checkYourAppointmentStatusDiv').hide();
            
                $('#registerFormDiv').hide();
                $('#registerButton').hide();
                $('#showAvailability').show();

                if ($("#role_name_hidden").val() == 'clinitian') {
                    $('#createAvailability').show();
                    $('#viewAllPatientAppointmentAction').show();
                    $('#bookDoctorAppointment').hide();
                    $('#checkYourAppointmentStatus').hide();
                } else {
                    $('#checkYourAppointmentStatus').show();
                    $('#createAvailability').hide();
                    $('#viewAllPatientAppointmentAction').hide();
                    $('#bookDoctorAppointment').show();
                }
                $('#allButtonDiv').show();
                $("#role_name").val(role);
                $("#existing_clinician_id").val($(this).val());


                $("#role_name_hidden").val(role);
                $("#existing_id_hidden").val($(this).val());

            });

            $('#createAvailability').click(function() {
                $('#calendar').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $('#registerAvailabilitySlotFormMessages').hide();
                if ($("#role_name").val() == 'clinitian') {
                    $('#createAvailabilityDiv').slideDown();
                   
                }
            });

            $('#showAvailability').click(function() {
                
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $('#bookDoctorAppointmentDiv').hide();
                $('#calendar').show();
                $('#checkYourAppointmentStatusDivMessages').hide();

                
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth', 
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: function(fetchInfo, successCallback, failureCallback) {
                        
                        $.ajax({
                            url: "{{ route('clinician.getAvailability') }}", 
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                role_name: $('#role_name_hidden').val(),
                                existing_id: $('#existing_id_hidden').val(),
                            },
                            dataType: 'json', 
                            success: function(response) {
                                var events = [];

                                
                                $.each(response.data, function(index, eventData) {
                                    var event = {
                                        title: eventData.title,
                                        start: eventData.availability_start,
                                        end: eventData.availability_end,
                                        backgroundColor: eventData.availability_background_color,
                                        borderColor: eventData.availability_border_color,
                                       
                                    };
                                    events.push(event);
                                });

                                
                                successCallback(events);
                            },
                            error: function(xhr, status, error) {
                                
                                console.error('Error fetching events:', error);
                                failureCallback(error); // Call failureCallback if needed
                            }
                        });
                    }
                });

                calendar.render();

            });

            $('#submitAvailabilitySlot').click(function(e) {
                e.preventDefault();
                
                var formData = $('#selectClinicianForm').serialize();

                $.ajax({
                    url: "{{ route('store.selected.clinician') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json', 
                    success: function(response) {
                        if (response.success) {
                            $('#registerAvailabilitySlotFormMessages').show();
                            $("#role_name_hidden").val(response.role_name);
                            $("#existing_id_hidden").val(response.existing_id);
                            $('#registerAvailabilitySlotFormMessages').html('<span>Availability created successfully!</span>'); // Display success 
                        } else {
                            $('#registerAvailabilitySlotFormMessages').show();
                            $('#registerAvailabilitySlotFormMessages').html('<span>Registration failed. Please try again.</span>'); // Display error message in a designated div
                        }
                        $('#selectClinicianForm')[0].reset(); // reset form
                    },
                    error: function(xhr, status, error) {
                        
                        $('#registerAvailabilitySlotFormMessages').show();
                        $('#registerAvailabilitySlotFormMessages').html('');
                        $('#registerAvailabilitySlotFormMessages').html('<span>' + xhr.responseJSON.message + '</span>');
                    }
                });
            });

            $('#viewAllPatientAppointmentAction').click(function() {
                $('#viewAllPatientAppointmentAction').slideDown();
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $('#bookDoctorAppointmentDiv').hide();
                $('#calendar').hide();
            });

            function fetchAvailability() {
                $('#viewAllPatientAppointmentAction').hide();
                $('#bookDoctorAppointmentDiv').slideDown();
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $('#calendar').hide();
                $('#checkYourAppointmentStatusDiv').hide();


                $.ajax({
                    url: "{{ route('show.appointment') }}",
                    type: 'get',
                    dataType: 'json', 
                    success: function(response) {
                       
                        if (response.success) {
                            $('#registerbookDoctorAppointmentFormMessages').show();
                           

                            var data = response.data;
                            var html = '';
                            if (data.length > 0) {
                                $.each(data, function(index, item) {
                                    html += '<tr>';
                                    html += '<td>' + item.clinitian_id + '</td>';
                                    html += '<td>' + item.title + '</td>';
                                    html += '<td>' + item.availability_start + '</td>';
                                    html += '<td>' + item.availability_end + '</td>';
                                    html += '<td>' + (item.appointment_status ? item.appointment_status : 'No action') + '</td>';
                                    html += '<td>';
                                    html += '<button class="btn btn-sm btn-success change-status-btn" data-id="' + item.id + '" data-clinitianid="' + item.clinitian_id + '" data-status="pending">Book Appointment</button>';
                                    html += '</td>';
                                    html += '</tr>';
                                });
                                $('#availabilityTableBody').html(html);
                            } else {
                                $('#availabilityTableBody').html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                            }
                        } else {
                            $('#registerbookDoctorAppointmentFormMessages').show();
                            $('#registerbookDoctorAppointmentFormMessages').html('<span>Registration failed. Please try again.</span>');
                        }
                        $('#selectClinicianForm')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        $('#registerbookDoctorAppointmentFormMessages').show();
                        $('#registerbookDoctorAppointmentFormMessages').html('');
                        $('#registerbookDoctorAppointmentFormMessages').html('<span>' + xhr.responseJSON.message + '</span>');
                    }
                });
            }

            $('#bookDoctorAppointment').click(function() {
                fetchAvailability();
            });

            $(document).on('click', '.change-status-btn', function() {
                var id = $(this).data('id');
                var clinitian_id = $(this).data('clinitianid');
                var status = $(this).data('status');

                $.ajax({
                    url: "{{ route('update.availability.status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        clinitian_id: clinitian_id,
                        patient_id: $("#existing_id_hidden").val(),
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Status updated successfully:', response.message);
                           
                            fetchAvailability();
                        } else {
                            console.error('Error updating status:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating status:', error);
                    }
                });
            });


            $('#checkYourAppointmentStatus').click(function() {
                $('#checkYourAppointmentStatusDiv').slideDown();
                $('#viewAllPatientAppointmentAction').hide();
                $('#bookDoctorAppointmentDiv').hide();
                $('#createAvailabilityDiv').hide();
                $('#viewAllPatientAppointmentDiv').hide();
                $('#calendar').hide();

                $.ajax({
                    url: "{{ route('check.appointment.status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        patient_id: $("#existing_id_hidden").val(),
                    },
                    dataType: 'json', 
                    success: function(response) {

                        if (response.success) {

                            $('#checkYourAppointmentStatusDivMessages').show();

                            var data = response.data;
                            if (data.length > 0) {
                                var html = '';
                                $.each(data, function(index, item) {
                                    html += '<tr>';
                                    html += '<td>' + item.clinitian_id + '</td>';
                                    html += '<td>' + item.title + '</td>';
                                    html += '<td>' + item.availability_start + '</td>';
                                    html += '<td>' + item.availability_end + '</td>';
                                    if(item.appointment_status == 'completed'){
                                        html += '<td>' + item.appointment_status + '</td>';
                                    }else{
                                        html += '<td>' + ((item.appointment_status == 'confirmed') ? item.appointment_status : 'No action from clinitian side') + '</td>';
                                    }
                                    
                                    html += '</tr>';
                                });
                                $('#appStatusdiv').html(html);
                            } else {
                                $('#appStatusdiv').html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                            }
                        } else {
                            $('#checkYourAppointmentStatusDivMessages').show();
                            $('#checkYourAppointmentStatusDivMessages').html('<span>Registration failed. Please try again.</span>'); 
                        }
                      
                    },
                    error: function(xhr, status, error) {

                        $('#checkYourAppointmentStatusDivMessages').show();
                        $('#checkYourAppointmentStatusDivMessages').html('');
                        $('#checkYourAppointmentStatusDivMessages').html('<span>' + xhr.responseJSON.message + '</span>');
                    }
                });
            });


            function funnew() {
                $('#viewAllPatientAppointmentDiv').slideDown();
                $('#bookDoctorAppointmentDiv').hide();
                $('#createAvailabilityDiv').hide();
                $('#calendar').hide();

                $.ajax({
                    url: "{{ route('check.all.patient.appointment.status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        clinitian_id: $("#existing_id_hidden").val(),
                    },
                    dataType: 'json', 
                    success: function(response) {

                        if (response.success) {

                            $('#viewAllPatientAppointmentDivMessages').show();

                            var data = response.data;
                            if (data.length > 0) {
                                var html = '';
                                $.each(data, function(index, item) {
                                    html += '<tr>';
                                    html += '<td>' + item.clinitian_id + '</td>';
                                    html += '<td>' + item.title + '</td>';
                                    html += '<td>' + item.availability_start + '</td>';
                                    html += '<td>' + item.availability_end + '</td>';
                                    
                                    // html += '<td>';
                                    if (item.appointment_status == 'pending') {
                                        html += '<td>' + item.appointment_status + '</td>';
                                        html += '<td>';
                                        html += '<button class="btn btn-sm btn-success change-patient-status-btn" data-id="' + item.id + '" data-clinitianid="' + item.clinitian_id + '" data-status="confirmed">confirmed</button>';
                                        html += '<button class="btn btn-sm btn-danger change-patient-status-btn ml-2" data-id="' + item.id + '" data-clinitianid="' + item.clinitian_id + '" data-status="cancelled">Cancel</button>';
                                        html += '</td>';
                                    } else if (item.appointment_status == 'confirmed') {
                                        html += '<td>' + item.appointment_status + '</td>';
                                        html += '<td>';
                                        html += '<button class="btn btn-sm btn-success change-patient-status-btn" data-id="' + item.id + '" data-clinitianid="' + item.clinitian_id + '" data-status="completed">completed</button>';
                                        html += '</td>';
                                    } else if (item.appointment_status == 'completed') {
                                        html += '<td>' + item.appointment_status + '</td>';
                                        html += '<td>' + item.appointment_status + '</td>';
                                    } else {
                                        html += '<td>No action</td>';
                                        html += '<td>No action</td>'; 
                                    }
                                    html += '</tr>';
                                });
                                $('#patientAppStatusdiv').html(html);
                            } else {
                                $('#patientAppStatusdiv').html('<tr><td colspan="6" class="text-center">No data found</td></tr>');
                            }
                        } else {
                            $('#viewAllPatientAppointmentDivMessages').show();
                            $('#viewAllPatientAppointmentDivMessages').html('<span>Registration failed. Please try again.</span>'); 
                        }
                    },
                    error: function(xhr, status, error) {

                        $('#viewAllPatientAppointmentDivMessages').show();
                        $('#viewAllPatientAppointmentDivMessages').html('');
                        $('#viewAllPatientAppointmentDivMessages').html('<span>' + xhr.responseJSON.message + '</span>');
                    }
                });
            }

            $('#viewAllPatientAppointmentAction').click(function() {
                funnew();
            });

            $(document).on('click', '.change-patient-status-btn', function() {

                var id = $(this).data('id');
                var status = $(this).data('status');

                $.ajax({
                    url: "{{ route('update.all.patient.appointment.status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        clinitian_id: $("#existing_id_hidden").val(),
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Status updated successfully:', response.message);
                            
                            funnew();
                        } else {
                            console.error('Error updating status:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating status:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>