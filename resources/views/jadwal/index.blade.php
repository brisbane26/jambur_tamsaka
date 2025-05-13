<x-admin-layout>
	<head>
		<nama_acara>Kalender Busuk</nama_acara>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
	</head>
	<body>
		<div class="container">
			<h1>Bagusi la lekk</h1>
			<div id='calendar'></div>
		</div>
		<script>
			$(document).ready(function () {
			   
			var SITEURL = "{{ url('/') }}";
			  
			$.ajaxSetup({
			    headers: {
			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
			  
			var calendar = $('#calendar').fullCalendar({
			                    editable: true,
			                    events: SITEURL + "/full-calender",
			                    displayEventTime: false,
			                    editable: true,
			                    eventRender: function (event, element, view) {
			                        if (event.allDay === 'true') {
			                                event.allDay = true;
			                        } else {
			                                event.allDay = false;
			                        }
			                    },
			                    selectable: true,
			                    selectHelper: true,
			                    select: function (tanggal, user_id, allDay) {
			                        var nama_acara = prompt('Event Title:');
			                        if (nama_acara) {
			                            var tanggal = $.fullCalendar.formatDate(tanggal, "Y-MM-DD");
			                            var user_id = $.fullCalendar.formatDate(user_id, "Y-MM-DD");
			                            $.ajax({
			                                url: SITEURL + "/full-calender-ajax",
			                                data: {
			                                    nama_acara: nama_acara,
			                                    tanggal: tanggal,
			                                    user_id: user_id,
			                                    type: 'add'
			                                },
			                                type: "POST",
			                                success: function (data) {
			                                    displayMessage("Event Created Successfully");
			  
			                                    calendar.fullCalendar('renderEvent',
			                                        {
			                                            id: data.id,
			                                            nama_acara: nama_acara,
			                                            tanggal: tanggal,
			                                            user_id: user_id,
			                                            allDay: allDay
			                                        },true);
			  
			                                    calendar.fullCalendar('unselect');
			                                }
			                            });
			                        }
			                    },
			                    eventDrop: function (event, delta) {
			                        var tanggal = $.fullCalendar.formatDate(event.tanggal, "Y-MM-DD");
			                        var user_id = $.fullCalendar.formatDate(event.user_id, "Y-MM-DD");
			  
			                        $.ajax({
			                            url: SITEURL + '/full-calender-ajax',
			                            data: {
			                                nama_acara: event.nama_acara,
			                                tanggal: tanggal,
			                                user_id: user_id,
			                                id: event.id,
			                                type: 'update'
			                            },
			                            type: "POST",
			                            success: function (response) {
			                                displayMessage("Event Updated Successfully");
			                            }
			                        });
			                    },
			                    eventClick: function (event) {
			                        var deleteMsg = confirm("Do you really want to delete?");
			                        if (deleteMsg) {
			                            $.ajax({
			                                type: "POST",
			                                url: SITEURL + '/full-calender-ajax',
			                                data: {
			                                        id: event.id,
			                                        type: 'delete'
			                                },
			                                success: function (response) {
			                                    calendar.fullCalendar('removeEvents', event.id);
			                                    displayMessage("Event Deleted Successfully");
			                                }
			                            });
			                        }
			                    }
			 
			                });
			 
			});
			 
			function displayMessage(message) {
			    toastr.success(message, 'Event');
			} 
			  
		</script>
//Techsolutionstuff
	</body>
</x-admin-layout>
