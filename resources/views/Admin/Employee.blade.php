<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>

<body>
    <div class="">
@include('Admin.layout.Navbar')
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12 col-md-4 mb-3">
                    <div class="card shadow rounded">
                        <div class="card-header">
                            <h5>เพิ่มรายชื่อ</h5>
                        </div>
                        <form method="POST" action="{{ route('Employee.Store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">ชื่อ-สกุล</label>
                                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                        name="fullname" id="name" value="{{ old('fullname') }}" required>
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" id="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" id="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">สถานะ</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status"
                                        id="status" required>
                                        <option value="" disabled selected>ตำแหน่ง</option>
                                        <option value="Admin" {{ old('status') == 'Admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="User" {{ old('status') == 'User' ? 'selected' : '' }}>User
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100">เพิ่มข้อมูล</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ตารางรายชื่อ -->
                <div class="col-12 col-md-8 mb-3">
                    <div class="card rounded shadow">
                        <div class="card-header">
                            <h5>รายชื่อ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="tableShow">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ชื่อ</th>
                                            <th>อีเมล</th>
                                            <th>วันที่สร้าง</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->fullname }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editModal-{{ $user->uuid }}">
                                                        แก้ไข
                                                    </button>
                                                    <form method="POST"
                                                        action="{{ route('Employee.Destroy', $user->uuid) }}"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm delete-btn">ลบ</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    ไม่มีข้อมูลพนักงาน</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modals -->
                @foreach ($users as $user)
                    <div class="modal fade" id="editModal-{{ $user->uuid }}" tabindex="-1"
                        aria-labelledby="editModalLabel-{{ $user->uuid }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('Employee.Update', $user->uuid) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-{{ $user->uuid }}">
                                            แก้ไขข้อมูลพนักงาน</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="uuid" value="{{ $user->uuid }}">
                                        <div class="mb-3">
                                            <label class="form-label">ชื่อ-สกุล</label>
                                            <input type="text" class="form-control" name="fullname"
                                                value="{{ $user->fullname }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">อีเมล</label>
                                            <input disabled type="email" class="form-control" name="email"
                                                value="{{ $user->email }}" required>
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label">Password (ถ้าไม่แก้ไข ให้เว้นว่าง)</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">สถานะ</label>
                                            <select class="form-select" name="status" required>
                                                <option value="Admin"
                                                    {{ $user->status == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="User"
                                                    {{ $user->status == 'User' ? 'selected' : '' }}>User</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">ปิด</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@include('Admin.layout.footer')


    <!-- SweetAlert -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: '{{ session('success') }}',
                position: 'top-end',
                toast: true,
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    <!-- DataTables init -->
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
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "ข้อมูลนี้จะถูกลบถาวร!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

</body>

</html>
