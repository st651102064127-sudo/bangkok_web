<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangkok Course - แพลตฟอร์มออนไลน์สำหรับองค์กร</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="hero-section">
        <div class="geometric-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        @include('user.navbar')
        <div class="main-content">
            <div class="content-left">
                <h1>ยกระดับทักษะทีมของคุณด้วย <span class="brand-highlight">Course</span></h1>
                <div class="subtitle">แพลตฟอร์มพัฒนาทักษะสำหรับองค์กรสมัยใหม่</div>
                <p class="main-message">
                    Bangkok Course มอบโซลูชั่นการฝึกอบรมแบบครบวงจรสำหรับองค์กรยุคใหม่
                    เพิ่มศักยภาพทีมงานด้วยคอร์สเรียนคุณภาพสูงจากผู้เชี่ยวชาญ
                    พร้อมระบบติดตามผลการฝึกอบรมแบบเรียลไทม์
                </p>
                <div class="cta-buttons">

                    <a href="{{ session('user_uuid') ? route('user.show', session('user_uuid')) : route('User.Login') }}"
                        class="cta-button">
                        <i class="fas fa-play-circle"></i> เริ่มต้นทันที
                    </a>
                    <a href={{ route('user.show') }} class="cta-button secondary">
                        <i class="fas fa-book-open"></i> ดูคอร์สทั้งหมด
                    </a>
                </div>
            </div>

            <div class="content-right">
                <div class="hero-image-container">
                    <div class="hero-image">
                        <div class="hero-stats">
                            <div class="stat-card">
                                <div class="stat-value">500+</div>
                                <div class="stat-label">คอร์สเรียนคุณภาพ</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">98%</div>
                                <div class="stat-label">ความพึงพอใจผู้ใช้</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">2,500+</div>
                                <div class="stat-label">องค์กรที่ไว้วางใจ</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">24/7</div>
                                <div class="stat-label">บริการสนับสนุน</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="features-section">
        <div class="section-header">
            <h2 class="section-title">ทำไมต้องเรา</h2>
            <p class="section-subtitle">
                แพลตฟอร์มพัฒนาทักษะที่ออกแบบมาเพื่อตอบโจทย์องค์กรยุคใหม่
                ด้วยเทคโนโลยีล้ำสมัยและเนื้อหาคุณภาพสูง
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3 class="feature-title">คอร์สเรียนคุณภาพสูง</h3>
                <p class="feature-description">
                    คอร์สเรียนที่ออกแบบโดยผู้เชี่ยวชาญด้านต่างๆ เนื้อหาอัปเดตตามเทรนด์ล่าสุด
                    พร้อมแบบฝึกหัดและโปรเจคจริง
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">วิเคราะห์ผลลัพธ์</h3>
                <p class="feature-description">
                    ระบบติดตามและวิเคราะห์ผลการเรียนแบบเรียลไทม์ ช่วยให้องค์กร
                    วัดประสิทธิภาพการฝึกอบรมได้อย่างแม่นยำ
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">เรียนได้ทุกที่ทุกเวลา</h3>
                <p class="feature-description">
                    เข้าถึงคอร์สเรียนได้จากทุกอุปกรณ์ ทั้งคอมพิวเตอร์ แท็บเล็ต และสมาร์ทโฟน
                    โดยไม่จำกัดเวลา
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="feature-title">สำหรับองค์กรโดยเฉพาะ</h3>
                <p class="feature-description">
                    ระบบจัดการผู้เรียนระดับองค์กร พร้อมฟีเจอร์กำหนดบทเรียนเฉพาะทีม
                    และระบบรายงานผลแบบครบวงจร
                </p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div>
                <a href="#" class="footer-logo">
                    <div class="footer-logo-icon">
                        <img src="img/Logo_บริษัท-removebg-preview.png" alt="BMS Logo"
                            style="width:100%; height:100%; object-fit:contain;">
                    </div>
                    Bangkok Web Solotion
                </a>
                <p class="footer-description">
                    Bangkok Web Cost คือแพลตฟอร์มพัฒนาทักษะสำหรับองค์กรยุคใหม่
                    ที่ช่วยยกระดับศักยภาพทีมงานด้วยคอร์สเรียนคุณภาพสูง
                    และระบบติดตามผลลัพธ์แบบเรียลไทม์
                </p>
                <div class="social-links">
                    <a href="https://www.facebook.com/BangkokWebSolution" class="social-link"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/channel/UC9YdXCaiyNGQXVxm1f1u9pQ" class="social-link"><i
                            class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div>
                <h4 class="footer-title">เมนูหลัก</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> หน้าแรก</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> คอร์สเรียนทั้งหมด</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> สำหรับองค์กร</a></li>
                    <li><a href="about.php"><i class="fas fa-chevron-right"></i> เกี่ยวกับเรา</a></li>
                    <li><a href="contace.php"><i class="fas fa-chevron-right"></i> ติดต่อเรา</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">หมวดหมู่คอร์ส</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> การพัฒนาทีม</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> เทคโนโลยี</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> การตลาด</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> การเงิน</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> การบริหาร</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">ข้อมูลติดต่อ</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> 182 หมู่บ้านตะวันรุ่ง 7 ถนนลาดพร้าว 64
                            แยก 4 แขวงวังทองหลาง เขตวังทองหลาง กรุงเทพฯ 10310</a></li>
                    <li><a href="#"><i class="fas fa-phone-alt"></i> 02-933-9750-1</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> info@bangkokwebsolution.com</a></li>
                    <li><a href="#"><i class="fas fa-clock"></i> จันทร์-ศุกร์ 9:00-17:30 น.</a></li>
                </ul>
            </div>
        </div>

        <div class="copyright">
            &copy; ลิขสิทธิ์ 2021 บริษัท บางกอก เว็บ โซลูชั่น จำกัด.
        </div>
    </footer>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: '{{ session('error') }}',
                confirmButtonText: 'ตกลง'
            })
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: '{{ session('success') }}',
                confirmButtonText: 'ตกลง'
            })
        </script>
    @endif
</body>

</html>
