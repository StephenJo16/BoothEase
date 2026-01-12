<footer class="bg-gray-900 text-white py-12">
    {{-- (Bagian footer content tetap sama) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex justify-center items-center mb-6">
            <img src="{{ asset('images/boothease-logo-cropped.webp') }}" alt="BoothEase" class="h-10 mr-3">
        </div>
        <p class="text-gray-400 mb-4">Making event booth booking simple and efficient</p>
        <p class="text-sm text-gray-500">All Rights Reserved ©</p>
    </div>
</footer>

{{-- Pastikan FontAwesome sudah di-load di layout utama Anda. Jika belum, uncomment baris ini: --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}

{{-- KODE NOTIFIKASI MODERN --}}
@if (session('success') || session('status'))
<div class="notification-popup success">
    <div class="icon-box">
        <i class="fa-solid fa-check"></i>
    </div>
    <div class="text-content">
        <h4 class="title">Success!</h4>
        <p class="message">{{ session('success') ?: session('status') }}</p>
    </div>
    <div class="progress-bar"></div>
</div>
@endif

@if (session('error'))
<div class="notification-popup error">
    <div class="icon-box">
        <i class="fa-solid fa-exclamation"></i>
    </div>
    <div class="text-content">
        <h4 class="title">Error!</h4>
        <p class="message">{{ session('error') }}</p>
    </div>
    <div class="progress-bar"></div>
</div>
@endif

<style>
    /* Base Notification Style */
    .notification-popup {
        position: fixed;
        top: 24px;
        right: 24px;
        display: flex;
        align-items: flex-start;
        padding: 16px;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 4px 6px rgba(0, 0, 0, 0.04);
        z-index: 9999;
        min-width: 320px;
        max-width: 400px;
        overflow: hidden;

        /* Font Settings */
        font-family: 'Lato', sans-serif;
        color: #1F2937;

        /* Animation Setup */
        opacity: 0;
        transform: translateX(50px);
        animation: slideInFade 5s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards;
    }

    /* Content Layout */
    .notification-popup .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin-right: 14px;
        flex-shrink: 0;
        color: white;
        font-size: 14px;
    }

    .notification-popup .text-content {
        flex: 1;
    }

    .notification-popup .title {
        font-weight: 700;
        font-size: 15px;
        margin: 0 0 2px 0;
        line-height: 1.4;
    }

    .notification-popup .message {
        font-size: 14px;
        color: #6B7280;
        margin: 0;
        line-height: 1.4;
    }

    /* Success Theme */
    .notification-popup.success {
        border-left: 4px solid #F97316;
        /* Orange Brand Color */
    }

    .notification-popup.success .icon-box {
        background: #F97316;
    }

    .notification-popup.success .title {
        color: #F97316;
    }

    .notification-popup.success .progress-bar {
        background-color: #F97316;
    }

    /* Error Theme */
    .notification-popup.error {
        border-left: 4px solid #EF4444;
        /* Red Color */
    }

    .notification-popup.error .icon-box {
        background: #EF4444;
    }

    .notification-popup.error .title {
        color: #EF4444;
    }

    .notification-popup.error .progress-bar {
        background-color: #EF4444;
    }

    /* Time Progress Bar (Optional Visual Flair) */
    .notification-popup .progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        opacity: 0.3;
        animation: progress 4.5s linear forwards;
        /* Durasi sedikit kurang dari total animasi */
    }

    /* Animations */
    @keyframes slideInFade {
        0% {
            opacity: 0;
            transform: translateX(100%);
        }

        10% {
            opacity: 1;
            transform: translateX(0);
        }

        90% {
            opacity: 1;
            transform: translateX(0);
        }

        100% {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    @keyframes progress {
        0% {
            width: 100%;
        }

        100% {
            width: 0%;
        }
    }
</style>