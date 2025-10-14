<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    @include('Admin.layout.navbar');
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .progress-bar {
            background-color: #4f46e5;
            transition: width 0.3s ease;
            font-size: 0.75rem;
            color: #fff;
            text-align: center;
        }

        body {
            background-color: #f9fafb;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-radius: 1rem 1rem 0 0 !important;
            background: #ffffff;
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .form-control,
        .btn {
            border-radius: 0.6rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-light {
            border: 1px solid #ddd;
        }

        .btn-danger {
            border: none;
        }
    </style>
    <div class="container mt-5" style="max-width: 900px;">
        <h2 class="mb-4 text-center fw-bold text-dark">เพิ่มคอร์สใหม่</h2>

        <form action="{{ route('Course.Store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- ข้อมูลคอร์ส -->
            <div class="card mb-4">
                <div class="card-header">ข้อมูลคอร์ส</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อคอร์ส</label>
                            <input type="text" name="title" class="form-control" placeholder="เช่น Web Development"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">หมวดหมู่</label>
                            <input type="text" name="category" class="form-control" placeholder="เช่น Programming">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ผู้สอน</label>
                            <input type="text" name="instructor" class="form-control" placeholder="ชื่อผู้สอน"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ราคา</label>
                            <input type="number" name="price" class="form-control" placeholder="0 = ฟรี" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ระดับ</label>
                            <input type="text" name="level" class="form-control" placeholder="Beginner / Advanced">
                        </div>

                        <!-- Upload Image -->
                        <div class="col-md-6">
                            <label class="form-label">รูปคอร์ส</label>
                            <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">

                        </div>

                        <div class="col-12">
                            <label class="form-label">รายละเอียด</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="เขียนรายละเอียดของคอร์ส..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Syllabus -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Course Syllabus</span>
                    <button type="button" id="addSyllabus" class="btn btn-light btn-sm">+ เพิ่มหัวข้อ</button>
                </div>
                <div class="card-body" id="syllabusWrapper">
                    <div class="row g-2 mb-3 syllabus-item">
                        <div class="col-md-4">
                            <input type="text" name="syllabuses[0][title]" class="form-control" placeholder="หัวข้อ"
                                required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="syllabuses[0][duration]" class="form-control"
                                placeholder="ระยะเวลา">
                        </div>
                        <div class="col-md-4">
                            <input type="file" class="form-control asyncVideoInput" accept="video/*">
                            <input type="hidden" name="syllabuses[0][video_url]" class="videoPath">

                            <div class="progress mt-2" style="height: 10px; display:none;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                            </div>

                            <small class="text-success uploadStatus" style="display:none;"></small>

                            <video class="mt-2 videoPreview" width="100%" height="150" controls
                                style="display:none;"></video>
                        </div>

                        <div class="col-md-1 d-flex align-items-start">
                            <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Course Features</span>
                    <button type="button" id="addFeature" class="btn btn-light btn-sm">+ เพิ่ม Feature</button>
                </div>
                <div class="card-body" id="featureWrapper">
                    <div class="row g-2 mb-2 feature-item">
                        <div class="col-md-5">
                            <input type="text" name="features[0][feature_name]" class="form-control"
                                placeholder="เช่น ภาษา" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="features[0][feature_value]" class="form-control"
                                placeholder="เช่น ไทย/อังกฤษ">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4 py-2">💾 บันทึกคอร์ส</button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
$(function() {
    let syllabusIndex = 1;
    let uploadingVideos = 0; // ตัวนับวิดีโอที่กำลังอัปโหลด

    // Add syllabus
    $("#addSyllabus").click(function() {
        $("#syllabusWrapper").append(`
        <div class="row g-2 mb-3 syllabus-item">
            <div class="col-md-4">
                <input type="text" name="syllabuses[${syllabusIndex}][title]" class="form-control" placeholder="หัวข้อ" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="syllabuses[${syllabusIndex}][duration]" class="form-control" placeholder="ระยะเวลา">
            </div>
            <div class="col-md-4">
                <input type="file" class="form-control asyncVideoInput" accept="video/*">
                <input type="hidden" name="syllabuses[${syllabusIndex}][video_url]" class="videoPath">

                <div class="progress mt-2" style="height: 10px; display:none;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                </div>

                <small class="text-success uploadStatus" style="display:none;"></small>

                <video class="mt-2 videoPreview" width="100%" height="150" controls style="display:none;"></video>
            </div>
            <div class="col-md-1 d-flex align-items-start">
                <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
            </div>
        </div>
        `);
        syllabusIndex++;
    });

    // Remove row
    $(document).on("click", ".removeRow", function() {
        $(this).closest(".row").remove();
    });

    // Upload video AJAX
    $(document).on("change", ".asyncVideoInput", function() {
        const file = this.files[0];
        const wrapper = $(this).closest(".col-md-4");
        const progressBar = wrapper.find(".progress");
        const progressInner = wrapper.find(".progress-bar");
        const status = wrapper.find(".uploadStatus");
        const hiddenPath = wrapper.find(".videoPath");
        const preview = wrapper.find(".videoPreview")[0];
        const formData = new FormData();

        if (!file) return;

        uploadingVideos++; // เริ่มนับอัปโหลด

        status.hide();
        progressBar.show();
        progressInner.css("width","0%").text("0%");

        formData.append("video", file);
        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: "{{ route('upload.video') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(e){
                    if (e.lengthComputable){
                        let percent = Math.round((e.loaded/e.total)*100);
                        progressInner.css("width", percent + "%").text(percent + "%");
                    }
                });
                return xhr;
            },
            success: function(res){
                if(res.status === 'success'){
                    hiddenPath.val(res.path);
                    preview.src = res.url;
                    preview.style.display = "block";
                    status.text("✅ อัปโหลดสำเร็จ").show();
                    progressInner.css("width","100%").text("100%");
                    setTimeout(()=>{ progressBar.fadeOut(); }, 800);
                }
            },
            error: function(){
                status.text("❌ อัปโหลดล้มเหลว").css("color","red").show();
                progressBar.hide();
            },
            complete: function() {
                uploadingVideos--; // ลดตัวนับเมื่ออัปโหลดเสร็จ
            }
        });
    });

    // ป้องกัน submit form ถ้ายังมีวิดีโออัปโหลด
    $("form").submit(function(e){
        if(uploadingVideos > 0){
            e.preventDefault();
            alert("กรุณารอการอัปโหลดวิดีโอให้เสร็จก่อน");
        }
    });
});
</script>

</body>

</html>
