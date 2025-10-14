<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อเรา | Bangkok Solutions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href={{asset('css/contect.css')}}>
</head>
<body>
    <main class="contact-container">
        <header class="contact-header">
            <h2>ติดต่อเรา</h2>
        </header>

        <div class="content-wrapper">
            <section class="form-section">
                <h3 style="display: none;">ส่งข้อความหาเรา</h3>
                <form id="contactForm">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            ชื่อ-นามสกุล
                        </label>
                        <input type="text" class="form-input" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            อีเมล
                        </label>
                        <input type="email" class="form-input" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">
                            <i class="fas fa-tag"></i>
                            หัวข้อ
                        </label>
                        <input type="text" class="form-input" id="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">
                            <i class="fas fa-comment"></i>
                            ข้อความ
                        </label>
                        <textarea class="form-textarea" id="message" rows="5" required></textarea>
                    </div>

                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i>
                        ส่งข้อความ
                    </button>
                </form>
            </section>

            <section class="contact-details">
                <h3>ช่องทางการติดต่ออื่นๆ</h3>
                <ul class="contact-list">
                    <li class="contact-item">
                        <div class="contact-icon-wrapper">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span>contact@bangkoksolutions.com</span>
                    </li>

                    <li class="contact-item">
                        <div class="contact-icon-wrapper">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <span>02-345-6789</span>
                    </li>

                    <li class="contact-item">
                        <div class="contact-icon-wrapper">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <span>Bangkok Solutions</span>
                    </li>

                    <li class="contact-item">
                        <div class="contact-icon-wrapper">
                            <i class="fab fa-line"></i>
                        </div>
                        <span>@bangkoksolutions</span>
                    </li>
                </ul>
            </section>
        </div>
    </main>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;

            if(name && email && subject && message) {
                alert('ส่งข้อความสำเร็จ! เราจะติดต่อกลับไปหาคุณในเร็วๆนี้');
                this.reset();
            } else {
                alert('กรุณากรอกข้อมูลให้ครบทุกช่อง');
            }
        });
    </script>
</body>
</html>
