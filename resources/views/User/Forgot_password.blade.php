<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน | Bangkok Solutions</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- ฟอนต์ -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: #f8f9fa;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            border-radius: 30px;
            font-weight: 500;
        }

        .form-control {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 col-md-6 col-lg-4">
            <div class="text-center mb-3">
                <h3 class="fw-bold">รีเซ็ตรหัสผ่าน</h3>
                <p class="text-muted">กรุณากรอกอีเมลเพื่อรับ OTP</p>
            </div>

            <!-- ฟอร์ม -->
            <form method="POST" action="{{ route('User.Forgot.Submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope"></i> อีเมล</label>
                    <div class="input-group">
                        <input id="email_account" name="email_account" type="email" class="form-control"
                            placeholder="กรอกอีเมลที่ลงทะเบียนไว้" required>
                        <button type="button" class="btn btn-primary btn-custom" onclick="sendOtp()">ส่ง OTP</button>
                    </div>
                </div>

                <!-- OTP (ซ่อนก่อน) -->
                <div id="otp-section" style="display:none;">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-key"></i> รหัส OTP</label>
                        <input id="otp_code" name="otp_code" type="text" class="form-control"
                            placeholder="กรอกรหัส OTP">
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-success btn-custom" onclick="verifyOtp()">ตรวจสอบ
                            OTP</button>
                    </div>
                </div>

                <!-- ฟอร์มเปลี่ยนรหัสผ่าน (ซ่อนก่อน) -->
                <div id="password-section" style="display:none;">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock"></i> รหัสผ่านใหม่</label>
                        <input id="new_password" name="new_password" type="password" class="form-control"
                            placeholder="กรอกรหัสผ่านใหม่">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-check-double"></i> ยืนยันรหัสผ่านใหม่</label>
                        <input id="confirm_password" name="confirm_password" type="password" class="form-control"
                            placeholder="กรอกรหัสผ่านใหม่อีกครั้ง">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-custom">
                            <i class="fas fa-sync-alt"></i> เปลี่ยนรหัสผ่าน
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function sendOtp() {
            const email = document.getElementById('email_account').value;
            if (!email) {
                Swal.fire("กรุณากรอกอีเมลก่อน");
                return;
            }

            const respone = await fetch('{{ route('send.otp') }}', {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email
                })
            });

            const data = await respone.json();
            if (data.success) {
                Swal.fire('ส่ง OTP ไปยังอีเมลเรียบร้อยแล้ว');
                document.getElementById('otp-section').style.display = 'block';
            } else {
                Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
            }
        }

        async function verifyOtp() {
            const email = document.getElementById('email_account').value;

            const otp = document.getElementById('otp_code').value;
            if (!otp) {
                Swal.fire("กรุณากรอก OTP");
                return;
            }
            console.log(email);
            console.log(otp);


            const respone = await fetch('{{ route('verify.otp') }}', {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email,
                    otp
                })
            });

            const data = await respone.json();
            if (data.success) {
                Swal.fire('OTP ถูกต้อง', '', 'success');
                document.getElementById('password-section').style.display = 'block';
            } else {
                Swal.fire('OTP ไม่ถูกต้อง', data.message, 'error');
            }
        }
    </script>
</body>

</html>
