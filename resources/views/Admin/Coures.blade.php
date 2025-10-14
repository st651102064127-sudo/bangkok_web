<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link href="//cdn.datatables.net/2.3.2/css/dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    @include('Admin.layout.navbar')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">รายชื่อคอร์ส</h4>
            </div>
            <div class="card-body">
                <table id="coursesTable" class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>รูป</th>
                            <th>ชื่อคอร์ส</th>
                            <th>ผู้สอน</th>
                            <th>ราคา</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $index => $course)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ $course->image_url ? asset($course->image_url) : 'https://via.placeholder.com/60' }}"
                                        alt="Course Image" width="60" height="60" class="rounded">
                                </td>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->instructor }}</td>
                                <td>{{ number_format($course->price, 2) }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm viewBtn" data-bs-toggle="modal"
                                        data-bs-target="#viewModal" data-title="{{ $course->title }}"
                                        data-id="{{ $course->uuid }}" data-category="{{ $course->category }}"
                                        data-instructor="{{ $course->instructor }}"
                                        data-duration="{{ $course->duration }}" data-level="{{ $course->level }}"
                                        data-price="{{ $course->price }}"
                                        data-description="{{ $course->description }}"
                                        data-image="{{ $course->image_url ? asset($course->image_url) : 'https://via.placeholder.com/150' }}"
                                        data-syllabuses='@json($course->syllabuses)'
                                        data-features='@json($course->features)'>
                                        View
                                    </button>

                                    <button class="btn btn-warning btn-sm editBtn" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="{{ $course->course_id }}"
                                        data-title="{{ $course->title }}" data-category="{{ $course->category }}"
                                        data-instructor="{{ $course->instructor }}" data-level="{{ $course->level }}"
                                        data-price="{{ $course->price }}"
                                        data-description="{{ $course->description }}"
                                        data-image="{{ $course->image_url ? asset($course->image_url) : '' }}"
                                        data-syllabuses='@json($course->syllabuses)'
                                        data-features='@json($course->features)'>
                                        Edit
                                    </button>

                                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $course->course_id }}"
                                        data-title="{{ $course->title }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold" id="editModalLabel">Edit Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                        <div class="row g-4">
                            <!-- Left: Image -->
                            <div class="col-md-4 text-center">
                                <img id="editImagePreview" src="https://via.placeholder.com/300"
                                    class="img-fluid rounded mb-3" style="max-height:300px;">
                                <input type="file" name="image" id="editImageInput" class="form-control mt-2"
                                    accept="image/*">
                            </div>

                            <!-- Right: Details -->
                            <div class="col-md-8">
                                <input type="hidden" name="uuid" id="editUuid">

                                <div class="mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" id="editTitle" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Category</label>
                                    <input type="text" name="category" id="editCategory" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Instructor</label>
                                    <input type="text" name="instructor" id="editInstructor" class="form-control"
                                        required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Level</label>
                                    <input type="text" name="level" id="editLevel" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Price</label>
                                    <input type="number" name="price" id="editPrice" class="form-control"
                                        min="0" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="editDescription" class="form-control"></textarea>
                                </div>

                                <hr>
                                <h6>Syllabuses</h6>
                                <div id="editSyllabusWrapper"></div>
                                <button type="button" id="addEditSyllabus" class="btn btn-light btn-sm mt-2">+
                                    เพิ่มหัวข้อ</button>

                                <hr>
                                <h6>Features</h6>
                                <div id="editFeatureWrapper"></div>
                                <button type="button" id="addEditFeature" class="btn btn-light btn-sm mt-2">+ เพิ่ม
                                    Feature</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="viewModalLabel">Course Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Left: Image -->
                        <div class="col-md-4 text-center">
                            <img id="modalImage" src="https://via.placeholder.com/300" class="img-fluid rounded"
                                style="max-height:300px;">
                        </div>

                        <!-- Right: Details -->
                        <div class="col-md-8">
                            <h4 id="modalTitle" class="fw-bold mb-3"></h4>
                            <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                            <p><strong>Instructor:</strong> <span id="modalInstructor"></span></p>
                            <p><strong>Duration:</strong> <span id="modalDuration"></span></p>
                            <p><strong>Level:</strong> <span id="modalLevel"></span></p>
                            <p><strong>Price:</strong> <span id="modalPrice"></span></p>
                            <p><strong>Description:</strong> <span id="modalDescription"></span></p>

                            <hr>
                            <h6 class="fw-bold mt-3">Syllabuses:</h6>
                            <div id="modalSyllabuses" class="accordion"></div>

                            <h6 class="fw-bold mt-3">Features:</h6>
                            <ul id="modalFeatures" class="list-group list-group-flush"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">ยืนยันการลบคอร์ส</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>คุณต้องการลบคอร์ส <strong id="deleteCourseTitle"></strong> จริงหรือไม่?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">ลบ</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#coursesTable').DataTable();

            // ===== View Modal =====
            $('.viewBtn').click(function() {
                const btn = $(this);
                $('#modalTitle').text(btn.data('title'));
                $('#modalCategory').text(btn.data('category'));
                $('#modalInstructor').text(btn.data('instructor'));
                $('#modalDuration').text(btn.data('duration'));
                $('#modalLevel').text(btn.data('level'));
                $('#modalPrice').text(btn.data('price'));
                $('#modalDescription').text(btn.data('description'));
                $('#modalImage').attr('src', btn.data('image'));

                // Syllabuses
                const syllabuses = btn.data('syllabuses');
                let syllabusHtml = '';
                if (syllabuses.length > 0) {
                    syllabuses.forEach((s, i) => {
                        const videoHTML = s.video_url ?
                            `<video controls width="100%" class="mt-2 rounded shadow-sm">
                                <source src="/${s.video_url}" type="video/mp4">
                                Your browser does not support the video tag.
                               </video>` :
                            `<small class="text-muted">ไม่มีวิดีโอ</small>`;
                        syllabusHtml += `
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="heading${i}">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse${i}" aria-expanded="false"
                                    aria-controls="collapse${i}">
                                    ${i+1}. ${s.title} (${s.duration || "ไม่ระบุเวลา"})
                                </button>
                            </h2>
                            <div id="collapse${i}" class="accordion-collapse collapse" aria-labelledby="heading${i}"
                                data-bs-parent="#modalSyllabuses">
                                <div class="accordion-body">${videoHTML}</div>
                            </div>
                        </div>`;
                    });
                } else {
                    syllabusHtml = '<p class="text-muted">ยังไม่มีบทเรียน</p>';
                }
                $('#modalSyllabuses').html(syllabusHtml);

                // Features
                const features = btn.data('features');
                let featureHtml = '';
                if (features.length > 0) {
                    features.forEach(f => {
                        featureHtml +=
                            `<li class="list-group-item"><strong>${f.feature_name}:</strong> ${f.feature_value}</li>`;
                    });
                } else {
                    featureHtml = '<li class="list-group-item text-muted">ไม่มีคุณสมบัติ</li>';
                }
                $('#modalFeatures').html(featureHtml);
            });



            // ===== Delete =====
            $('.deleteBtn').click(function() {
                const btn = $(this);
                $('#deleteCourseTitle').text(btn.data('title'));
                $('#deleteForm').attr('action', '/courses/delete/' + btn.data('id'));
            });

            // ===== Session Messages =====
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            let syllabusIndex = 100; // สำหรับ syllabus ใหม่
            let featureIndex = 100; // สำหรับ feature ใหม่

            // เปิด Edit Modal
            $('.editBtn').click(function() {
                const btn = $(this);

                $('#editForm').attr('action', '/courses/update/' + btn.data('id'));
                $('#editTitle').val(btn.data('title'));
                $('#editCategory').val(btn.data('category'));
                $('#editInstructor').val(btn.data('instructor'));
                $('#editLevel').val(btn.data('level'));
                $('#editPrice').val(btn.data('price'));
                $('#editDescription').val(btn.data('description'));
                $('#editImagePreview').attr('src', btn.data('image') || '');

                // Syllabuses
                const syllabuses = btn.data('syllabuses');
                let syllabusHtml = '';
                syllabuses.forEach((s, index) => {
                    syllabusHtml += `
            <div class="row g-2 mb-2 syllabus-item">
                <div class="col-md-4">
                    <input type="text" name="syllabuses[${index}][title]" class="form-control" value="${s.title}" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="syllabuses[${index}][duration]" class="form-control" value="${s.duration || ''}">
                </div>
                <div class="col-md-4">
                    <input type="file" class="form-control asyncVideoInput" accept="video/*">
                    <input type="hidden" name="syllabuses[${index}][video_url]" class="videoPath" value="${s.video_url || ''}">
                    <video class="mt-2 videoPreview" width="100%" height="150" controls style="display:${s.video_url ? 'block':'none'}" src="${s.video_url || ''}"></video>
                    <div class="progress mt-2" style="height:10px; display:none;">
                        <div class="progress-bar" role="progressbar" style="width:0%"></div>
                    </div>
                    <small class="text-success uploadStatus" style="display:none;"></small>
                </div>
                <div class="col-md-1 d-flex align-items-start">
                    <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
                </div>
            </div>`;
                });
                $('#editSyllabusWrapper').html(syllabusHtml);

                // Features
                const features = btn.data('features');
                let featureHtml = '';
                features.forEach((f, index) => {
                    featureHtml += `
            <div class="row g-2 mb-2 feature-item">
                <div class="col-md-5">
                    <input type="text" name="features[${index}][feature_name]" class="form-control" value="${f.feature_name}" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="features[${index}][feature_value]" class="form-control" value="${f.feature_value}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
                </div>
            </div>`;
                });
                $('#editFeatureWrapper').html(featureHtml);
            });

            // เพิ่ม Syllabus ใหม่
            $('#addEditSyllabus').click(function() {
                $('#editSyllabusWrapper').append(`
        <div class="row g-2 mb-2 syllabus-item">
            <div class="col-md-4">
                <input type="text" name="syllabuses[${syllabusIndex}][title]" class="form-control" placeholder="หัวข้อ" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="syllabuses[${syllabusIndex}][duration]" class="form-control" placeholder="ระยะเวลา">
            </div>
            <div class="col-md-4">
                <input type="file" class="form-control asyncVideoInput" accept="video/*">
                <input type="hidden" name="syllabuses[${syllabusIndex}][video_url]" class="videoPath">
                <video class="mt-2 videoPreview" width="100%" height="150" controls style="display:none;"></video>
                <div class="progress mt-2" style="height:10px; display:none;">
                    <div class="progress-bar" role="progressbar" style="width:0%"></div>
                </div>
                <small class="text-success uploadStatus" style="display:none;"></small>
            </div>
            <div class="col-md-1 d-flex align-items-start">
                <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
            </div>
        </div>`);
                syllabusIndex++;
            });

            // เพิ่ม Feature ใหม่
            $('#addEditFeature').click(function() {
                $('#editFeatureWrapper').append(`
        <div class="row g-2 mb-2 feature-item">
            <div class="col-md-5">
                <input type="text" name="features[${featureIndex}][feature_name]" class="form-control" placeholder="เช่น ภาษา" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="features[${featureIndex}][feature_value]" class="form-control" placeholder="เช่น ไทย/อังกฤษ">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 removeRow">ลบ</button>
            </div>
        </div>`);
                featureIndex++;
            });

            // ลบแถว
            $(document).on('click', '.removeRow', function() {
                $(this).closest('.row').remove();
            });

            // Preview & Async Upload Video
            // Preview & Async Upload Video
      let uploadingVideos = 0; // ตัวนับวิดีโอที่กำลังอัปโหลด

$(document).on('change', '.asyncVideoInput', function() {
    const file = this.files[0];
    const wrapper = $(this).closest('.col-md-4');
    const preview = wrapper.find('.videoPreview')[0];
    const hiddenPath = wrapper.find('.videoPath');
    const status = wrapper.find('.uploadStatus');
    const progressBar = wrapper.find('.progress');
    const progressInner = wrapper.find('.progress-bar');

    if (!file) return;

    uploadingVideos++; // เพิ่มตัวนับเมื่อเริ่มอัปโหลด

    status.hide();
    progressBar.show();
    progressInner.css('width', '0%').text('0%');

    const oldVideo = hiddenPath.val() || '';

    let formData = new FormData();
    formData.append('video', file);
    formData.append('old_video', oldVideo);
    formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

    $.ajax({
        url: "{{ route('video.update') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    let percent = Math.round((e.loaded / e.total) * 100);
                    progressInner.css('width', percent + '%').text(percent + '%');
                }
            });
            return xhr;
        },
        success: function(res) {
            if (res.status === 'success') {
                preview.src = res.url;
                preview.style.display = 'block';
                hiddenPath.val(res.path);
                status.text('✅ อัปโหลดสำเร็จ').show();
                progressInner.css('width', '100%').text('100%');
            } else {
                status.text('❌ อัปโหลดล้มเหลว').css('color', 'red').show();
            }
        },
        error: function() {
            status.text('❌ อัปโหลดล้มเหลว').css('color', 'red').show();
        },
        complete: function() {
            progressBar.fadeOut();
            uploadingVideos--; // ลดตัวนับเมื่ออัปโหลดเสร็จ
        }
    });
});

// ป้องกันการ submit form ถ้าวิดีโอกำลังอัปโหลด
$('#editForm').submit(function(e) {
    if (uploadingVideos > 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณารอการอัปโหลดวิดีโอให้เสร็จก่อน',
            timer: 2000,
            showConfirmButton: false
        });
    }
});

// ป้องกันการปิด modal ถ้ายังมีวิดีโอกำลังอัปโหลด
$('#editModal').on('hide.bs.modal', function(e) {
    if (uploadingVideos > 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณารอการอัปโหลดวิดีโอให้เสร็จก่อน',
            timer: 2000,
            showConfirmButton: false
        });
    }
});

        });
    </script>
</body>

</html>
