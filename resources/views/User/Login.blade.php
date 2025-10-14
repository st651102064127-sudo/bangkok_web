<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | Bangkok Solutions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="login-container">
        <div class="logo-container">
            <img src={{ asset('Image/Logo.png') }} alt="BMS Logo">
        </div>

        <h1>เข้าสู่ระบบ</h1>


        <form action={{ route('User.login.session') }} method="POST">
            @csrf
            <div class="input-group">
                <input type="email" id="email" name="email_account" placeholder=" " required>
                <label for="email">อีเมล</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password_account" placeholder=" " required>
                <label for="password">รหัสผ่าน</label>
                <span class="toggle-password" onclick="togglePassword('password', this)"><i
                        class="fas fa-eye"></i></span>
            </div>



            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i>
                เข้าสู่ระบบ
            </button>

            <div class="divider">หรือ</div>

            <a href="{{ route('User.register') }}" class="register-link">
                <i class="fas fa-user-plus"></i>
                สร้างบัญชีใหม่
            </a>
            <a href="{{ route('Forgot_password') }}" class="">
                ลืมรหัสผ่าน
            </a>

        </form>
    </div>

    <script>
        //... (JavaScript ส่วนเดิม) ...
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const fontAwesomeIcon = icon.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                fontAwesomeIcon.classList.remove('fa-eye');
                fontAwesomeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                fontAwesomeIcon.classList.remove('fa-eye-slash');
                fontAwesomeIcon.classList.add('fa-eye');
            }
        }

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                if (this.validity.valid && this.value.length > 0) {
                    this.style.borderColor = 'var(--success)';
                } else if (this.value.length > 0) {
                    this.style.borderColor = 'var(--error)';
                } else {
                    this.style.borderColor = 'var(--border)';
                }
            });
        });
    </script>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorMessage = '';

                @foreach ($errors->all() as $error)
                    errorMessage += '{{ $error }}<br>';
                @endforeach

                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    html: errorMessage,
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            })
        </script>
    @endif
</body>

</html>
