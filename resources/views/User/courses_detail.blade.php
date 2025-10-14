<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $result['title'] }} | รายละเอียดคอร์ส</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ============================================ */
        /* BASE STYLES - Modern Lighter Theme */
        /* ============================================ */
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* ============================================ */
        /* MAIN CONTAINER */
        /* ============================================ */
        .course-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* ============================================ */
        /* COURSE CARD - Glass Morphism Design */
        /* ============================================ */
        .course-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* ============================================ */
        /* COURSE IMAGE */
        /* ============================================ */
        .course-image-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .course-image-wrapper:hover {
            transform: translateY(-5px);
        }

        .course-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            display: block;
        }

        /* ============================================ */
        /* PRICE BOX */
        /* ============================================ */
        .price-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            margin-top: 1.5rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .price-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .price-amount {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        /* ============================================ */
        /* COURSE INFO SECTION */
        /* ============================================ */
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .course-title {
            color: #2d3748;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .course-description {
            color: #4a5568;
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        /* ============================================ */
        /* COURSE META INFO */
        /* ============================================ */
        .meta-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            color: #4a5568;
            font-size: 1rem;
        }

        .meta-item i {
            color: #667eea;
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        /* ============================================ */
        /* BUTTONS */
        /* ============================================ */
        .btn-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* ============================================ */
        /* ALERTS & BADGES */
        /* ============================================ */
        .alert-login {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
        }

        .badge-enrolled {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        /* ============================================ */
        /* SYLLABUS SECTION */
        /* ============================================ */
        .syllabus-header {
            color: #2d3748;
            font-size: 2rem;
            font-weight: 700;
            margin: 3rem 0 2rem 0;
            padding-bottom: 1rem;
            border-bottom: 3px solid #667eea;
        }

        .syllabus-item {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .syllabus-item:hover {
            transform: translateX(10px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
            border-left-color: #764ba2;
        }

        .syllabus-title {
            color: #2d3748;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .syllabus-duration {
            color: #718096;
            font-size: 0.95rem;
        }

        .locked-content {
            color: #e53e3e;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .video-player {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 350px;
        }

        /* ============================================ */
        /* DIVIDER */
        /* ============================================ */
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            margin: 3rem 0;
            border: none;
        }

        /* ============================================ */
        /* RESPONSIVE */
        /* ============================================ */
        @media (max-width: 768px) {
            .course-title {
                font-size: 1.8rem;
            }

            .course-card {
                padding: 1.5rem;
            }

            .meta-info {
                flex-direction: column;
                gap: 1rem;
            }

            .syllabus-item {
                flex-direction: column !important;
            }

            .video-player {
                margin-top: 1rem;
                max-width: 100%;
            }
        }

        /* ============================================ */
        /* ANIMATIONS */
        /* ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .course-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>

<body>

    <div class="course-container">
        <div class="course-card">
            <div class="row g-4">

                <!-- LEFT COLUMN: Image & Price -->
                <div class="col-lg-5">
                    <div class="course-image-wrapper">
                        <img src="{{ asset($result['image_url'] ?? 'placeholder.jpg') }}" class="course-img"
                            alt="{{ $result['title'] ?? 'Course Image' }}">
                    </div>

                    <div class="price-box">
                        <p class="price-label">ราคาคอร์ส</p>
                        <h3 class="price-amount">฿{{ number_format($result['price'] ?? 0, 0) }}</h3>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Course Info -->
                <div class="col-lg-7">
                    <span class="category-badge">{{ $result['category'] ?? 'Category' }}</span>

                    <h1 class="course-title">{{ $result['title'] ?? 'Course Title' }}</h1>

                    <p class="course-description">{{ $result['description'] ?? 'Course description...' }}</p>

                    <div class="meta-info">
                        <div class="meta-item">
                            <i class="fas fa-user-tie"></i>
                            <span>{{ $result['instructor'] ?? 'Unknown' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-signal"></i>
                            <span>{{ $result['level'] ?? 'All Levels' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="far fa-clock"></i>
                            <span>{{ $result['duration'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="action-section">
                        @if (empty(session('user_uuid')))
                            <!-- Not Logged In -->
                            <div class="alert-login">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                กรุณาเข้าสู่ระบบก่อนดำเนินการซื้อคอร์ส
                            </div>
                            <a href="{{ url('/login') }}" class="btn-primary-gradient me-3">
                                <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบเพื่อซื้อ
                            </a>
                        @else
                            <!-- Logged In -->
                            @if ($enrollment ?? false)
                                <!-- Already Enrolled -->
                                <span class="badge-enrolled">
                                    <i class="fas fa-check-circle me-2"></i>คุณได้ลงทะเบียนคอร์สนี้แล้ว
                                </span>
                                <br>
                                <a href="#syllabus-content" class="btn-primary-gradient me-3">
                                    <i class="fas fa-play-circle me-2"></i>เริ่มเรียน
                                </a>
                            @else
                                <!-- Not Enrolled Yet -->
                                <a href="{{ route('user.payment', ['id' => $result['course_id'] ?? 0]) }}"
                                    class="btn-primary-gradient me-3"
                                    onclick="return confirm('คุณต้องการซื้อคอร์ส {{ $result['title'] ?? '' }} ใช่หรือไม่?');">
                                    <i class="fas fa-credit-card me-2"></i>ซื้อคอร์สนี้เลย!
                                </a>
                            @endif
                        @endif

                        <a href="{{ route('user.show') }}" class="btn-outline-custom">
                            <i class="fas fa-arrow-left me-2"></i>ย้อนกลับ
                        </a>
                    </div>
                </div>
            </div>

            <hr class="divider">

            <!-- SYLLABUS SECTION -->
            <h3 class="syllabus-header" id="syllabus-content">
                <i class="fas fa-book-open me-2"></i>สารบัญบทเรียน
            </h3>

            @if (isset($result['syllabuses']))
                @foreach ($result['syllabuses'] as $syllabus)
                    <div class="syllabus-item d-flex flex-column flex-md-row justify-content-between align-items-start">
                        <div class="flex-grow-1 me-3">
                            <div class="syllabus-title">{{ $syllabus['title'] ?? 'บทเรียน' }}</div>
                            <div class="syllabus-duration">
                                <i class="far fa-clock me-1"></i>{{ $syllabus['duration'] ?? 'N/A' }}
                            </div>
                        </div>

                        @if (($enrollment ?? false) && ($syllabus['video_url'] ?? false))
                            <video width="350" controls class="video-player">
                                <source src="{{ asset($syllabus['video_url']) }}" type="video/mp4">
                                เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                            </video>
                        @else
                            <span class="locked-content">
                                <i class="fas fa-lock"></i>
                                <span>ต้องลงทะเบียนเรียนก่อนจึงจะดูได้</span>
                            </span>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>ไม่มีรายการบทเรียนสำหรับคอร์สนี้
                </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
