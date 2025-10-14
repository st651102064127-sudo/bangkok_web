<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>รายการลงทะเบียนทั้งหมด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>
<body>
    @include('admin.layout.navbar')
    <div class="container mt-5">
        <div class="card h-100 w-100">
            <div class="card-header">
                <h2>รายการลงทะเบียนทั้งหมด</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="tableShow">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ชื่อคอร์ส</th>
                            <th>วันที่ลงทะเบียน</th>
                            <th>สถานะการชำระเงิน</th>
                            <th>ยอดชำระ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $index => $registration)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $registration->course_id ?? 'ไม่พบข้อมูลคอร์ส' }}</td>
                            <td>{{ $registration->created_at }}</td>
                            <td class="text-capitalize">{{ $registration->status }}</td>
                            <td>{{ $registration->payment_amount ? number_format($registration->payment_amount, 2) . ' บาท' : 'ยังไม่ชำระ' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">ไม่มีข้อมูลที่จะแสดง</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.layout.footer')

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script>
        let table = new DataTable('#tableShow', {
            language: {
                search: "ค้นหา:",
                lengthMenu: "แสดง _MENU_ รายการต่อหน้า",
                info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                infoEmpty: "ไม่มีข้อมูลที่จะแสดง",
                infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
                zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
                paginate: {
                    first: "หน้าแรก",
                    last: "หน้าสุดท้าย",
                    next: "ถัดไป",
                    previous: "ย้อนกลับ"
                },
                emptyTable: "ไม่มีข้อมูลในตาราง",
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            responsive: true,
            ordering: true,
        });
    </script>
</body>
</html>
