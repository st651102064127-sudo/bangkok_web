<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คอร์สเรียนทั้งหมด - EduTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href={{ asset('css/courses_user.css') }}>
</head>

<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="text-center text-white">
                <h1 class="hero-title">เรียนรู้สิ่งใหม่ไปด้วยกัน</h1>
                <p class="hero-subtitle">ค้นพบคอร์สเรียนคุณภาพสูงจากผู้เชี่ยวชาญ</p> <!-- Search -->
                <div class="search-container">
                    <div class="position-relative"> <i class="fas fa-search search-icon"></i> <input type="text"
                            id="searchInput" class="form-control search-bar"
                            placeholder="ค้นหาคอร์ส, หมวดหมู่, หรือผู้สอน..."> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container"> <!-- Statistics -->
        <div class="stats-container">
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" id="totalCourses">0</div>
                        <div>คอร์สทั้งหมด</div>
                    </div>
                </div>


            </div>
        </div>

        <div class="container mt-4">
            <div class="row" id="coursesContainer"></div>

            <div id="noResults" class="no-results text-center d-none mt-4">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h4>ไม่พบคอร์ส</h4>
                <p>ลองใช้คำค้นหาอื่นหรือเลือกหมวดหมู่ต่างกัน</p>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // แปลง Laravel $data เป็น JS array
            const coursesData = @json($data);

            const searchInput = document.getElementById('searchInput');
            const coursesContainer = document.getElementById('coursesContainer');
            const totalCourses = document.getElementById('totalCourses');
            const noResults = document.getElementById('noResults');

            // ฟังก์ชันสร้าง card ของแต่ละคอร์ส
            function renderCourses(courses) {
                coursesContainer.innerHTML = '';
                if (courses.length === 0) {
                    noResults.classList.remove('d-none');
                    totalCourses.innerText = 0;
                    return;
                } else {
                    noResults.classList.add('d-none');
                }

                const row = document.createElement('div');
                row.classList.add('row', 'g-3'); // g-3 คือ spacing ระหว่าง card

                courses.forEach(course => {
                    const col = document.createElement('div');
                    col.classList.add('col-12', 'col-sm-6', 'col-md-4'); // responsive

                    const card = document.createElement('div');
                    card.classList.add('card', 'h-100', 'text-dark', 'shadow-sm');
                    card.style.background = 'rgba(255, 255, 255, 0.1)';

                    card.innerHTML = `
            <img src="${course.image_url}" class="card-img-top" alt="${course.title}" style="height:150px; object-fit:cover;">

            <div class="card-body bg-white p-2">
                <hr/>
                <h6 class="card-title mb-1">${course.title}</h6>
                <p class="card-text small h1 mb-1 text-truncate" title="${course.description}">${course.description}</p>
                <p class="mb-1 small"><strong>ผู้สอน:</strong> ${course.instructor}</p>
                <p class="mb-1 small"><strong>หมวดหมู่:</strong> ${course.category} | <strong>ระดับ:</strong> ${course.level} | <strong>เวลาเรียน:</strong> ${course.duration}</p>
                        <p class="mb-2 small h3"><strong>ราคา:</strong> ${course.price == 0 ? 'Free' : course.price}</p>

                <a href="/Courses/detail/${course.course_id}" class="btn btn-sm btn-primary w-100">ดูรายละเอียด</a>
            </div>
        `;

                    col.appendChild(card);
                    row.appendChild(col);
                });

                coursesContainer.appendChild(row);
                totalCourses.innerText = courses.length;
            }
            // แสดงคอร์สทั้งหมดตอนโหลดหน้า
            renderCourses(coursesData);

            // ฟังก์ชันค้นหา
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                const filteredCourses = coursesData.filter(course =>
                    course.title.toLowerCase().includes(query) ||
                    course.category.toLowerCase().includes(query) ||
                    course.instructor.toLowerCase().includes(query)
                );
                renderCourses(filteredCourses);
            });
        });
    </script>
</body>

</html>
