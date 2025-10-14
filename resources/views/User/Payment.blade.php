
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap Bundle (JS + Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href={{ asset('css/payment.css') }}>
<div class="container my-5 position-relative">

    <!-- ปุ่มย้อนกลับ -->
    <div class="back-btn">
        <a href="{{ url()->previous() }}" class="btn-back">
            ⬅ ย้อนกลับ
        </a>
    </div>

    <div class="row">
        <!-- ด้านซ้าย -->
        <div class="col-md-5 text-center mb-4">

            @if ($course['price'] == 0)
                <!-- ถ้าฟรี -->
                <h4 class="mb-4">คอร์สนี้ฟรี!</h4>
                <form action="{{route('free')}}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value={{$course['course_id']}}>
                    <button type="submit" class="btn-free-course">
                        🎉 รับคอร์สฟรีทันที
                    </button>
                </form>
            @else
                <!-- ถ้าเสียเงิน -->
                <h4>ชำระเงินด้วย QR Code</h4>

                <div class="qr-box mb-3">
                    {!! QrCode::size(300)->backgroundColor(255, 255, 255)->color(33, 37, 41)->merge(public_path('Image/Logo.png'), 1, true)->generate($qrcode) !!}
                </div>
                <p>สแกน QR Code เพื่อชำระเงิน</p>

                <!-- ฟอร์มอัปโหลดใบเสร็จ -->
                <form enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="receipt" class="form-label">อัปโหลดใบเสร็จ</label>
                        <input type="file" name="receipt" id="receipt" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="Submit">ส่งใบเสร็จเพื่อตรวจสอบ</button>
                </form>
            @endif
        </div>

        <!-- ด้านขวา: รายละเอียดคอร์ส + แผนการเรียน -->
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">{{ $course['title'] }}</h3>
                    <p class="card-text">{{ $course['description'] }}</p>
                    <p><strong>หมวดหมู่:</strong> {{ $course['category'] }}</p>
                    <p><strong>ผู้สอน:</strong> {{ $course['instructor'] }}</p>
                    <p><strong>ความยาวคอร์ส:</strong> {{ $course['duration'] }} ชั่วโมง</p>
                    <p><strong>ระดับ:</strong> {{ $course['level'] }}</p>
                    <p><strong>ราคา:</strong> {{ $course['price'] == 0 ? 'ฟรี' : $course['price'] . ' บาท' }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    แผนการเรียน
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($course['syllabuses'] as $syllabus)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $syllabus['title'] }}</span>
                            <span>{{ $syllabus['duration'] }} ชั่วโมง</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('Submit').addEventListener('click', async (e) => {
    e.preventDefault();

    const fileInput = document.getElementById('receipt');
    if (!fileInput.files.length) {
        Swal.fire({
            title: 'กรุณาเลือกรูปสลิปก่อน',
            icon: 'warning',
        });
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('course_id', {{ $course['course_id'] }});
    formData.append('amount', {{ $course['price'] }});
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('user_id', '{{ Session('user_uuid') }}');

    try {
        const response = await fetch('/api/verify-slip', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        console.log('API Response:', data);

        if (data.status === 'error' || response.status >= 400) {
            Swal.fire({
                title: data.message || 'เกิดข้อผิดพลาด',
                text: data.detail || 'ไม่สามารถตรวจสอบสลิปได้',
                icon: 'error',
            });
        } else if (data.status === 'success') {
            Swal.fire({
                title: 'ตรวจสอบสำเร็จ!',
                text: 'ระบบยืนยันการชำระเงินเรียบร้อยแล้ว',
                icon: 'success',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "/Courses/detail/" + {{ $course['course_id'] }};
                }
            });
        } else {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'ระบบไม่สามารถประมวลผลข้อมูลได้',
                icon: 'error',
            });
        }

    } catch (err) {
        console.error('Fetch error:', err);
        Swal.fire({
            title: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์',
            text: err.message,
            icon: 'error',
        });
    }
});
</script>
