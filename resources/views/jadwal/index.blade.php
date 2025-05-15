<x-admin-layout>

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
        <style>
            .fc-event {
                background-color: #007bff !important;
                /* Warna biru */
                color: #000 !important;
                /* Teks hitam */
                padding: 2px 4px;
                border-radius: 5px;
            }

            .fc-event .fc-title {
                background-color: #ffffff !important;
                /* Label putih */
                color: #000 !important;
                padding: 2px 4px;
                border-radius: 3px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>Kalender Busuk</h1>
            <div id='calendar'></div>
        </div>

        <script>
            $(document).ready(function() {
                var SITEURL = "{{ url('/') }}";

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var calendar = $('#calendar').fullCalendar({
                    editable: true,
                    events: SITEURL + "/jadwal",
                    displayEventTime: false,
                    selectable: true,
                    selectHelper: true,
                    select: function(start, end, allDay) {
                        var today = moment();
                        var minDate = today.add(3, 'days');

                        if (moment(start).isBefore(minDate)) {
                            toastr.error("Pesan tanggal minimal 3 hari setelah hari ini");
                            calendar.fullCalendar('unselect');
                            return;
                        }

                        var nama_acara = prompt('Masukkan Nama Acara:');
                        if (nama_acara) {
                            var tanggal = moment(start).format("YYYY-MM-DD");
                            var user_id = "{{ auth()->user()->id }}";

                            $.ajax({
                                url: SITEURL + "/jadwal",
                                data: {
                                    nama_acara: nama_acara,
                                    tanggal: tanggal,
                                    user_id: user_id,
                                    type: 'add'
                                },
                                type: "POST",
                                success: function(data) {
                                    calendar.fullCalendar('renderEvent', {
                                        id: data.id,
                                        title: nama_acara,
                                        start: tanggal,
                                        user_id: user_id,
                                        allDay: allDay
                                    }, true);
                                    calendar.fullCalendar('unselect');
                                    toastr.success("Acara berhasil ditambahkan");
                                },
                                error: function(xhr) {
                                    var error = JSON.parse(xhr.responseText);
                                    toastr.error(error.error);
                                }
                            });
                        }
                    },
                    eventClick: function(event) {
                        var isAdmin = @json(auth()->user()->hasRole('admin'));
                        var isOwner = event.user_id == "{{ auth()->user()->id }}";

                        if (isAdmin || isOwner) {
                            var nama_acara = prompt("Edit Nama Acara:", event.title);

                            if (nama_acara !== null) {
                                $.ajax({
                                    url: SITEURL + "/jadwal",
                                    data: {
                                        id: event.id,
                                        nama_acara: nama_acara,
                                        type: 'update'
                                    },
                                    type: "POST",
                                    success: function(response) {
                                        event.title = nama_acara;
                                        calendar.fullCalendar('updateEvent', event);
                                        toastr.success("Nama acara berhasil diperbarui");
                                    },
                                    error: function(xhr) {
                                        var error = JSON.parse(xhr.responseText);
                                        toastr.error(error.error);
                                    }
                                });
                            }

                            if (confirm("Yakin ingin menghapus acara ini?")) {
                                $.ajax({
                                    url: SITEURL + "/jadwal",
                                    data: {
                                        id: event.id,
                                        type: 'delete'
                                    },
                                    type: "POST",
                                    success: function(response) {
                                        calendar.fullCalendar('removeEvents', event.id);
                                        toastr.success("Acara berhasil dihapus");
                                    },
                                    error: function(xhr) {
                                        var error = JSON.parse(xhr.responseText);
                                        toastr.error(error.error);
                                    }
                                });
                            }
                        } else {
                            toastr.error("Anda tidak memiliki izin untuk mengelola acara ini");
                        }
                    }
                });
            });
        </script>

</x-admin-layout>
