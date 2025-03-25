@extends('layouts.app')

@section('content')
    <div class="scroll-container">
        <!-- Hero Section -->
        <section class="section hero">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1>Tingkatkan<br><span class="text-primary">Produktivitasmu</span></h1>
                        <p>Kelola tugas dengan cepat dan efisien menggunakan sistem manajemen tugas yang super cepat.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="hero-image">
                            <i class="fas fa-bolt bolt-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Power Section -->
        <section class="section power-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 order-md-2">
                        <h2>Optimalkan Alur Kerjamu</h2>
                        <p class="lead mb-4">Rasakan kecepatan dan efisiensi manajemen tugas modern.</p>
                        <div class="feature-list">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Pembuatan tugas super cepat</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Pembaruan dan notifikasi real-time</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Kolaborasi tim yang lancar</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 order-md-1">
                        <div class="power-image">
                            <i class="fas fa-rocket rocket-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta">
            <div class="container text-center">
                <h2>Siap Untuk Meningkatkan Produktivitas?</h2>
                <p class="lead mb-4">Bergabung dengan ribuan pengguna yang telah bekerja lebih cerdas.</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg action-btn">Mulai Sekarang <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </section>
    </div>
@endsection

@section('styles')
    <style>
        /* Reset default scrolling */
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }

        /* Main container styling */
        .scroll-container {
            scroll-snap-type: y mandatory;
            overflow-y: auto;
            height: 100vh;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Hide scrollbar */
        .scroll-container::-webkit-scrollbar {
            display: none;
        }

        .scroll-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        :root {
            --primary-color: #0099ff;
            --secondary-color: #001f3f;
            --accent-color: #00f2ff;
        }

        /* Section Styling */
        .section {
            min-height: 100vh;
            height: 100vh;
            display: flex;
            align-items: center;
            scroll-snap-align: start;
            scroll-snap-stop: always;
            position: relative;
            overflow: hidden;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero p {
            font-size: 1.25rem;
            color: #6c757d;
            margin-bottom: 2rem;
            max-width: 500px;
        }

        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
        }

        .bolt-icon {
            font-size: 15rem;
            color: var(--primary-color);
            transform: rotate(15deg);
            animation: float 3s ease-in-out infinite;
        }

        /* Power Section */
        .power-section {
            background: white;
        }

        .power-section h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
        }

        .feature-list {
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .feature-item i {
            color: var(--primary-color);
            margin-right: 1rem;
            font-size: 1.5rem;
        }

        .power-image {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
        }

        .rocket-icon {
            font-size: 10rem;
            color: var(--primary-color);
            animation: float 3s ease-in-out infinite alternate;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
        }

        .cta h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        /* Action Buttons */
        .action-btn {
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: transform 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Animations */
        @keyframes float {
            0% {
                transform: translateY(0px) rotate(15deg);
            }
            50% {
                transform: translateY(-20px) rotate(15deg);
            }
            100% {
                transform: translateY(0px) rotate(15deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 3rem;
            }
            
            .power-section h2 {
                font-size: 2.5rem;
            }

            .cta h2 {
                font-size: 2.5rem;
            }

            .bolt-icon {
                font-size: 10rem;
            }

            .rocket-icon {
                font-size: 8rem;
            }

            .section {
                padding: 0;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
@endsection
