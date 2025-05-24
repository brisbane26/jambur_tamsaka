<x-admin-layout>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
        <style>
            /* Gaya kalender */
            #calendar {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                padding: 20px;
            }
            
            /* Gaya header kalender */
            .fc-header-toolbar {
                margin-bottom: 1em;
            }
            
            /* Gaya sel tanggal */
            .fc-day {
                border: 1px solid #ddd !important;
                transition: background-color 0.2s;
            }
            
            .fc-day:hover {
                background-color: #f5f5f5;
            }
            
            /* Gaya event */
            .fc-event {
                background-color: #007bff !important;
                color: #000 !important;
                padding: 2px 4px;
                border-radius: 5px;
                border: none !important;
                cursor: pointer !important;
            }
            
            .fc-event .fc-title {
                background-color: #ffffff !important;
                color: #000 !important;
                padding: 2px 4px;
                border-radius: 3px;
                font-weight: bold;
            }
            
            /* Gaya hari ini */
            .fc-today {
                background-color: #fff8e1 !important;
            }
            
            /* Gaya modal */
            .modal-footer .btn {
                min-width: 80px;
            }

            /* Kotak tanggal merah jika ada event */
            .fc-day.has-event {
                background-color: #f87272 !important;
                color: #fff !important;
                position: relative;
            }
            /* Label acara putih, tulisan hitam */
            .event-label {
                display: inline-block;
                background: #fff;
                color: #222;
                border-radius: 4px;
                padding: 2px 8px;
                font-weight: bold;
                font-size: 13px;
                margin-top: 2px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            }
            .fc-event {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        </style>
    </head>

    <body>
        <div class="container p-6">
            <h1 class="text-2xl font-bold mb-4">Kalender Acara</h1>
            <div id='calendar'></div>
        </div>

        <!-- Modal untuk Acara -->
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Kelola Acara</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="eventForm">
                            <div class="form-group">
                                <label for="eventDate">Tanggal Acara</label>
                                <input type="text" class="form-control" id="eventDate" readonly>
                            </div>
                            <div class="form-group">
                                <label for="eventName">Nama Acara</label>
                                <input type="text" class="form-control" id="eventName" required>
                            </div>
                            <input type="hidden" id="eventId">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="deleteEvent">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="updateEvent">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        
        <script>
            $(document).ready(function () {
                var SITEURL = "{{ url('/') }}";
                var isAdmin = @json(auth()->user()->hasRole('admin'));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "5000"
                };

                var calendar = $('#calendar').fullCalendar({
                    events: SITEURL + "/jadwal",
                    displayEventTime: false,
                    selectable: false, // Nonaktifkan seleksi tanggal
                    editable: false,
                    eventDurationEditable: false,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    dayClick: function(date, jsEvent, view) {
                        // Hanya admin yang bisa interaksi
                        if (!isAdmin) return false;
                        
                        // Cari event di tanggal yang diklik
                        var events = $('#calendar').fullCalendar('clientEvents', function(event) {
                            return moment(event.start).format('YYYY-MM-DD') === moment(date).format('YYYY-MM-DD');
                        });
                        
                        // Jika ada event, tampilkan modal edit
                        if (events.length > 0) {
                            var event = events[0];
                            $('#eventModalLabel').text('Kelola Acara');
                            $('#eventDate').val(moment(event.start).format('DD MMMM YYYY'));
                            $('#eventName').val(event.title);
                            $('#eventId').val(event.id);
                            $('#eventModal').modal('show');
                        }
                        
                        return false;
                    },
                    eventClick: function(event, jsEvent) {
                        if (!isAdmin) return false;
                        
                        $('#eventModalLabel').text('Kelola Acara');
                        $('#eventDate').val(moment(event.start).format('DD MMMM YYYY'));
                        $('#eventName').val(event.title);
                        $('#eventId').val(event.id);
                        $('#eventModal').modal('show');
                        
                        return false;
                    },
                    eventRender: function(event, element) {
                        // Kotak tanggal merah untuk tanggal yang ada event
                        var dateString = moment(event.start).format('YYYY-MM-DD');
                        var cell = $(".fc-day[data-date='" + dateString + "']");
                        cell.addClass('has-event');
                        // Label acara putih, tulisan hitam
                        element.find('.fc-title').wrap('<span class="event-label"></span>');
                    }
                });

                // Handle update event
                $('#updateEvent').click(function() {
                    var eventName = $('#eventName').val().trim();
                    var eventId = $('#eventId').val();
                    
                    if (!eventName) {
                        toastr.error("Nama acara harus diisi");
                        return;
                    }

                    $.ajax({
                        url: SITEURL + "/jadwal",
                        data: {
                            id: eventId,
                            nama_acara: eventName,
                            type: 'update'
                        },
                        type: "POST",
                        success: function (data) {
                            var event = calendar.fullCalendar('clientEvents', eventId)[0];
                            if (event) {
                                event.title = data.title;
                                calendar.fullCalendar('updateEvent', event);
                            }
                            $('#eventModal').modal('hide');
                            toastr.success("Nama acara berhasil diperbarui");
                        },
                        error: function (xhr) {
                            var error = JSON.parse(xhr.responseText);
                            toastr.error(error.error);
                        }
                    });
                });

                // Handle hapus event
                $('#deleteEvent').click(function() {
                    if (confirm('Apakah Anda yakin ingin membatalkan acara ini?')) {
                        var eventId = $('#eventId').val();

                        $.ajax({
                            url: SITEURL + "/jadwal",
                            data: {
                                id: eventId,
                                type: 'delete'
                            },
                            type: "POST",
                            success: function () {
                                calendar.fullCalendar('removeEvents', eventId);
                                $('#eventModal').modal('hide');
                                toastr.success("Acara berhasil dibatalkan");
                            },
                            error: function (xhr) {
                                var error = JSON.parse(xhr.responseText);
                                toastr.error(error.error);
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</x-admin-layout>