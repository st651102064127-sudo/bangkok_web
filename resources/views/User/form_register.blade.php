<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน | Bangkok Solutions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/form_register.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="logo-section">
            <img src="{{ asset('Image/Logo.png') }}" alt="BMS Logo" style="width:50%; height:100%; object-fit:contain;">
            <h1>สร้างบัญชีผู้ใช้ใหม่</h1>
            <p class="subtitle">เข้าสู่ระบบเพื่อใช้งานบริการคอร์สเรียนทั้งหมด</p>
        </div>

        <form id="registerForm">
            @if ($errors->any())
                <div class="alert-danger">
                    <ul style="margin:0; padding-left: 1.2em;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @csrf
            <div class="form-group">
                <label for="username"><i class="fas fa-user label-icon"></i>ชื่อผู้ใช้</label>
                <div class="input-container">
                    <input id="username" name="username" type="text" placeholder="กรอกชื่อผู้ใช้" required>
                    <i class="fas fa-check-circle input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope label-icon"></i>อีเมล</label>
                <div class="input-container">
                    <input id="email" name="email" type="email" placeholder="example@bangkoksolutions.com"
                        required>
                    <i class="fas fa-at input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="Password1"><i class="fas fa-lock label-icon"></i>รหัสผ่าน</label>
                <div class="input-container">
                    <input id="Password1" name="password" type="password" placeholder="สร้างรหัสผ่าน" required>
                    <i class="fas fa-eye password-toggle input-icon" onclick="togglePassword('Password1', this)"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="Password2"><i class="fas fa-check-double label-icon"></i>ยืนยันรหัสผ่าน</label>
                <div class="input-container">
                    <input id="Password2" name="password_confirmation" type="password"
                        placeholder="กรอกรหัสผ่านอีกครั้ง" required>
                    <i class="fas fa-eye password-toggle input-icon" onclick="togglePassword('Password2', this)"></i>
                </div>
            </div>

            <!-- OTP container แบบ hidden แต่ไม่ใช้ display:none -->
            <div id="otpContainer" class="form-group" style="visibility:hidden; height:0; overflow:hidden;">
                <label for="otp"><i class="fas fa-key label-icon"></i>กรอก OTP ที่ส่งไปยังอีเมล</label>
                <div class="input-container">
                    <input id="otp" name="otp" type="text" placeholder="กรอกรหัส OTP">
                    <i class="fas fa-check-circle input-icon"></i>
                </div>
            </div>
            <div class="mb-3" id="otpContainer" style="visibility: hidden; height: 0;">
                <label for="otp" class="form-label">รหัส OTP</label>
                <input type="text" class="form-control" id="otp" name="otp">

                <!-- ปุ่ม resend (ซ่อนตอนเริ่ม) -->
                <button type="button" id="resendOtp" class="btn btn-link p-0 mt-2" style="display:none;">
                    ส่งรหัสอีกครั้ง
                </button>
            </div>
            <button type="submit" id="submitBtn" class="submit-btn">
                <i class="fas fa-user-plus"></i> สร้างบัญชี
            </button>
        </form>
    </div>

    <script>
        // ฟังก์ชัน toggle password
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Submit form แบบ dynamic
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const otpContainer = document.getElementById('otpContainer');
            const email = document.getElementById('email').value;
            const otp = document.getElementById('otp').value;

            // ถ้า OTP ยังไม่แสดง -> ส่ง OTP ก่อน
            if (otpContainer.style.visibility === 'hidden') {
                if (!email) {
                    Swal.fire('กรุณากรอกอีเมลก่อน');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerText = 'กำลังส่ง OTP...';

                try {
                    const res = await fetch('{{ route('send.otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire('ส่ง OTP ไปยังอีเมลเรียบร้อยแล้ว');
                        otpContainer.style.visibility = 'visible';
                        otpContainer.style.height = 'auto';
                        document.getElementById('otp').setAttribute('required', '');

                        // ✅ แสดงปุ่มส่งอีกครั้ง
                        document.getElementById('resendOtp').style.display = 'inline-block';

                        submitBtn.innerText = 'ยืนยัน OTP และสมัคร';
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('เกิดข้อผิดพลาดในการส่ง OTP');
                } finally {
                    submitBtn.disabled = false;
                }

            } else {
                // OTP แสดงแล้ว -> verify และ submit form
                if (!otp) {
                    Swal.fire('กรุณากรอก OTP');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerText = 'กำลังตรวจสอบ OTP...';

                try {
                    const res = await fetch('{{ route('verify.otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email,
                            otp
                        })
                    });
                    const data = await res.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP ถูกต้อง!',
                            text: 'กำลังสมัครสมาชิก...',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // รอ 2 วิ แล้ว submit เลย (ไม่ต้องรอ Swal ปิด)
                        setTimeout(async () => {
                            const form = document.getElementById('registerForm');
                            const formData = new FormData(form); // ใช้ form แทน this
                            submitBtn.disabled = true;
                            submitBtn.innerText = 'กำลังสมัครสมาชิก...';

                            try {
                                const res = await fetch('{{ route('register.submit') }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                });

                                const data = await res.json();

                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'สมัครสมาชิกสำเร็จ!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.href = '{{ route('User.Login') }}';
                                    });
                                } else if (data.errors) {
                                    // แสดง Validation errors
                                    let messages = '';
                                    for (const key in data.errors) {
                                        messages += data.errors[key].join('<br>') + '<br>';
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        html: messages
                                    });
                                    submitBtn.disabled = false;
                                    submitBtn.innerText = 'ยืนยัน OTP และสมัคร';
                                } else {
                                    // ข้อผิดพลาดทั่วไป
                                    Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่ทราบสาเหตุ',
                                        'error');
                                    submitBtn.disabled = false;
                                    submitBtn.innerText = 'ยืนยัน OTP และสมัคร';
                                }

                            } catch (err) {
                                console.error(err);
                                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถสมัครสมาชิกได้', 'error');
                                submitBtn.disabled = false;
                                submitBtn.innerText = 'ยืนยัน OTP และสมัคร';
                            }

                        }, 2000);

                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'ยืนยัน OTP และสมัคร';
                    }

                } catch (err) {
                    console.error(err);
                    Swal.fire('เกิดข้อผิดพลาดในการตรวจสอบ OTP');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'ยืนยัน OTP และสมัคร';
                }
            }
        });
    </script>
</body>

</html>
