 <nav>
     <a href="#" class="logo">
         <div class="logo-icon">
             <img src={{ asset('Image/Logo.png') }} alt="BMS Logo" style="width:100%; height:100%; object-fit:contain;">
         </div>
         Bangkok Web Solotion
     </a>
     <div class="nav-actions">
         <a href={{ route('index') }} class="nav-link">หน้าแรก</a>
         <a href="#" class="nav-link">คอร์สเรียน</a>
         <a href="#" class="nav-link">สำหรับองค์กร</a>
         <a href={{ route('about') }} class="nav-link">เกี่ยวกับเรา</a>
         <a href="/contace" class="nav-link">ติดต่อ</a>

         @if (session('user_fullname'))
             <div class="greeting">

                 <i class="fas fa-user-circle"></i> สวัสดีคุณ {{ session('user_fullname') }}

             </div>
         @endif
         @if (session('user_fullname'))
             <form action={{ route('logout') }} method="post">
                 @csrf
                 <button class="logout-btn"> <i class="fas fa-sign-out-alt"></i>
                     ออกจากระบบ
                 </button>
             </form>
         @else
             <a href={{ route('User.Login') }} class="login-btn-nav">
                 <i class="fas fa-sign-in-alt"></i>
                 เข้าสู่ระบบ
             </a>
         @endif




     </div>
 </nav>
