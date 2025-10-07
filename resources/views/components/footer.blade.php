<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex justify-center items-center mb-6">
            <img src="{{ asset('images/boothease-logo-cropped.png') }}" alt="BoothEase" class="h-10 mr-3">
        </div>
        <p class="text-gray-400 mb-4">Making event booth booking simple and efficient</p>
        <p class="text-sm text-gray-500">All Rights Reserved ©</p>
    </div>
</footer>

{{-- KODE NOTIFIKASI --}}
@if (session('success'))
    <div class="notification-popup success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="notification-popup error">
        {{ session('error') }}
    </div>
@endif

<style>
    .notification-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        z-index: 9999;
        opacity: 0;
        transform: translateY(-20px);
        animation: fadeInSlideDown 4s ease-in-out forwards;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: #ffffff;
        color: #1F2937;
        
        /* UPDATED FONT */
        font-family: 'Lato', sans-serif; 
    }

    .notification-popup.success {
        border: 2px solid #F97316; 
    }

    .notification-popup.error {
        border: 2px solid #dc3545;
    }

    @keyframes fadeInSlideDown {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        10% {
            opacity: 1;
            transform: translateY(0);
        }
        90% {
            opacity: 1;
            transform: translateY(0);
        }
        100% {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
</style>