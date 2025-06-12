  <link rel="icon" type="image/png" href="{{ asset('images/favicon-removebg-preview.png') }}" />
<x-admin-layout>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
        <style>
                nav a {
        text-decoration: none !important;
        border-bottom: none !important;
    }

    nav a:hover {
        text-decoration: none !important;
        border-bottom: none !important;
    }

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
        <div class="container p-1">
            <h3 class="text-gray-700 text-3xl font-medium">Kalender Acara</h3>
            <div id='calendar'></div>
        </div>

        <!-- Modal untuk Acara -->
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Acara</h5>
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
                        <input type="text" class="form-control" id="eventName" readonly> 
                        </div>
                        </form>
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
        // Periksa apakah user memiliki peran admin. Jika tidak, set isAdmin ke false.
        // auth()->user() akan mengembalikan objek User jika login, null jika tidak.
        // hasRole('admin') adalah metode dari Spatie Permission, jadi pastikan model User menggunakan trait HasRoles.
        var isAdmin = @json(auth()->user()->hasRole('admin'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        var calendar = $('#calendar').fullCalendar({
            events: SITEURL + "/jadwal",
            displayEventTime: false,
            selectable: false,
            editable: false,
            eventDurationEditable: false,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            dayClick: function(date, jsEvent, view) {
                // Hanya admin yang bisa berinteraksi dengan modal dari dayClick
                if (!isAdmin) return false;
                
                var events = $('#calendar').fullCalendar('clientEvents', function(event) {
                    return moment(event.start).format('YYYY-MM-DD') === moment(date).format('YYYY-MM-DD');
                });
                
                if (events.length > 0) {
                    var event = events[0];
                    $('#eventModalLabel').text('Acara');
                    $('#eventDate').val(moment(event.start).format('DD MMMM YYYY'));
                    // Gunakan properti originalTitle untuk mengisi nama acara di modal
                    $('#eventName').val(event.originalTitle); 
                    $('#eventId').val(event.id);
                    $('#eventModal').modal('show');
                }
                
                return false;
            },
            eventClick: function(event, jsEvent) {
                // Hanya admin yang bisa berinteraksi dengan modal dari eventClick
                if (!isAdmin) return false;
                
                $('#eventModalLabel').text('Acara');
                $('#eventDate').val(moment(event.start).format('DD MMMM YYYY'));
                // Gunakan properti originalTitle untuk mengisi nama acara di modal
                $('#eventName').val(event.originalTitle); 
                $('#eventId').val(event.id);
                $('#eventModal').modal('show');
                
                return false;
            },
            eventRender: function(event, element) {
                var dateString = moment(event.start).format('YYYY-MM-DD');
                var cell = $(".fc-day[data-date='" + dateString + "']");
                cell.addClass('has-event');
                // Tidak ada perubahan di sini, karena .fc-title akan menampilkan event.title
                element.find('.fc-title').wrap('<span class="event-label"></span>');
            }
        });

        // Tombol Update Event
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
                    nama_acara: eventName, // Kirim nama acara yang diinput dari modal
                    type: 'update'
                },
                type: "POST",
                success: function (data) {
                    var event = calendar.fullCalendar('clientEvents', eventId)[0];
                    if (event) {
                        // Perbarui properti event di frontend
                        event.title = data.title; // Ini akan menjadi nama_acara jika tidak ada nama gedung
                        event.originalTitle = data.originalTitle; // Perbarui juga originalTitle
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

        // Tombol Hapus di modal utama → buka modal konfirmasi hapus
        $('#deleteEvent').click(function() {
            $('#confirmDeleteModal').modal('show');
        });

        // Tombol Hapus di modal konfirmasi → eksekusi hapus AJAX
        $('#confirmDeleteBtn').click(function() {
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
                    $('#confirmDeleteModal').modal('hide');
                    toastr.success("Acara berhasil dibatalkan");
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);
                    toastr.error(error.error);
                    $('#confirmDeleteModal').modal('hide');
                }
            });
        });
    });
</script>
    </body>
</x-admin-layout>
