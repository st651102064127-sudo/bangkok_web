<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดจัดการคอร์ส</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #7c3aed;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #1f2937;
            --darker-bg: #111827;
            --light-text: #f9fafb;
            --border-color: #374151;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--dark-bg) 0%, var(--darker-bg) 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            border-right: 1px solid var(--border-color);
            height: 100vh;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(124,58,237,0.6);
            border-radius: 3px;
        }

        .sidebar .nav-link {
            color: #d1d5db !important;
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: rgba(79, 70, 229, 0.2);
            color: var(--light-text) !important;
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            background: #f9fafb;
            border-radius: 20px 0 0 0;
            min-height: 100vh;
            box-shadow: 0 0 40px rgba(0,0,0,0.05);
        }

        .page-title {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Card */
        .stat-card, .card {
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            will-change: transform, box-shadow;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .icon-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3);
        }

        .icon-success {
            background: linear-gradient(135deg, var(--success-color), #059669);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }

        .icon-warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
        }

        .icon-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 20px 24px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-inactive {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .status-paid {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-canceled {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .table-responsive {
            max-height: 300px;
            overflow-y: auto;
        }

        .table-dark {
            background: transparent;
        }

        .table-dark th {
            background: rgba(31, 41, 55, 0.8);
            color: white;
            border-color: var(--border-color);
            font-weight: 600;
            padding: 16px;
        }

        .table-dark td {
            background: rgba(249, 250, 251, 0.05);
            border-color: rgba(55, 65, 81, 0.3);
            padding: 16px;
            color: #374151;
        }

        .table-hover tbody tr:hover td {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .chart-container {
            position: relative;
            height: 250px;
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }

        .chart-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            text-align: center;
        }

        .welcome-banner {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1));
            border: 1px solid rgba(79, 70, 229, 0.2);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* Animate fade-in smooth */
        .animate-fade-in {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .animate-fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1050;
                left: -250px;
                width: 250px;
                transition: left 0.3s ease;
            }

            .main-content {
                border-radius: 0;
                margin-left: 0;
            }

            .page-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3">
            <div class="sidebar vh-100 text-light p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white fw-bold">
                        <i class="fas fa-graduation-cap me-2"></i>Admin Panel
                    </h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href={{route('Employee.Index')}}><i class="fas fa-users me-2"></i> พนักงาน</a>
                    <a class="nav-link" href={{route('Courses.Index')}}><i class="fas fa-book me-2"></i> คอร์ส</a>
                    <a class="nav-link" href={{route('registrations.index')}}><i class="fas fa-clipboard-list me-2"></i> การลงทะเบียน</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="main-content p-4">
                <div class="welcome-banner animate-fade-in">
                    <h5 class="mb-2">ยินดีต้อนรับสู่ระบบจัดการคอร์ส</h5>
                    <p class="text-muted mb-0">จัดการข้อมูลคอร์ส พนักงาน และการลงทะเบียนได้อย่างง่ายดาย</p>
                </div>

                <h1 class="page-title animate-fade-in">แดชบอร์ดจัดการคอร์ส</h1>

                <!-- Key Metrics -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card animate-fade-in position-relative">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="metric-icon icon-primary me-3"><i class="fas fa-users"></i></div>
                                <div>
                                    <div class="stat-number text-primary">{{ $totalUsers }}</div>
                                    <div class="stat-label">ผู้ใช้ทั้งหมด</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="stat-card animate-fade-in position-relative">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="metric-icon icon-success me-3"><i class="fas fa-book-open"></i></div>
                                <div>
                                    <div class="stat-number text-success">{{ $totalCourses }}</div>
                                    <div class="stat-label">คอร์สทั้งหมด</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card animate-fade-in position-relative">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="metric-icon icon-warning me-3"><i class="fas fa-clipboard-check"></i></div>
                                <div>
                                    <div class="stat-number text-warning">{{ $totalRegistrations }}</div>
                                    <div class="stat-label">การลงทะเบียน</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="stat-card animate-fade-in position-relative">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="metric-icon icon-success me-3"><i class="fas fa-dollar-sign"></i></div>
                                <div>
                                    <div class="stat-number text-success">฿{{ number_format($totalRevenue) }}</div>
                                    <div class="stat-label">รายได้รวม</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Latest Users and Registrations -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card animate-fade-in">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-users me-2"></i>ผู้ใช้ล่าสุด</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>ชื่อ</th>
                                                <th>Email</th>
                                                <th>สถานะ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($latestUsers as $user)
                                            <tr>
                                                <td>{{ $user->fullname }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="status-badge {{ $user->status == 'Active' ? 'status-active' : 'status-inactive' }}">
                                                        {{ $user->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card animate-fade-in">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clipboard-list me-2"></i>การลงทะเบียนล่าสุด</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>คอร์ส</th>
                                                <th>ราคา</th>
                                                <th>สถานะ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($latestRegistrations as $reg)
                                            <tr>
                                                <td>{{ $reg->course_title }}</td>
                                                <td>฿{{ number_format($reg->course_price) }}</td>
                                                <td>
                                                    <span class="status-badge
                                                        @if($reg->status=='Paid') status-paid
                                                        @elseif($reg->status=='Pending') status-pending
                                                        @else status-canceled @endif">
                                                        {{ $reg->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Animate fade-in
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.animate-fade-in');
    animatedElements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('show');
        }, index * 100);
    });
});

// User Status Chart
const userStatusCtx = document.getElementById('userStatusChart').getContext('2d');
new Chart(userStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Inactive'],
        datasets: [{
            data: [{{ $activeUsers }}, {{ $inactiveUsers }}],
            backgroundColor: ['rgba(16, 185, 129, 0.8)','rgba(107, 114, 128, 0.8)'],
            borderColor: ['rgba(16, 185, 129, 1)','rgba(107, 114, 128, 1)'],
            borderWidth: 2
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{padding:20,usePointStyle:true} } } }
});

// Payment Status Chart
const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
new Chart(paymentStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending', 'Canceled'],
        datasets: [{
            data: [{{ $paidRegistrations }}, {{ $pendingRegistrations }}, {{ $canceledRegistrations }}],
            backgroundColor: ['rgba(16, 185, 129, 0.8)','rgba(245, 158, 11, 0.8)','rgba(239, 68, 68, 0.8)'],
            borderColor: ['rgba(16, 185, 129, 1)','rgba(245, 158, 11, 1)','rgba(239, 68, 68, 1)'],
            borderWidth: 2
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{padding:20,usePointStyle:true} } } }
});
</script>
</body>
</html>
