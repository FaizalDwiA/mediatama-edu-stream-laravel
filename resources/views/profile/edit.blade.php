<x-app-layout>
    @push('styles')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

            /* Change main page font and style */
            .profile-wrapper {
                font-family: 'Outfit', sans-serif !important;
                color: #f8fafc !important;
            }

            /* Page header styling override */
            header.bg-white {
                background-color: #0b0f19 !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
                box-shadow: none !important;
            }

            header.bg-white h2 {
                color: #ffffff !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 700 !important;
            }

            /* Card container styling */
            .profile-card {
                background-color: #12121a !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25) !important;
                border-radius: 16px !important;
                padding: 2rem !important;
            }

            /* Headers inside forms */
            .profile-card h2,
            .fixed.inset-0.z-50 h2 {
                color: #ffffff !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 600 !important;
                font-size: 1.15rem !important;
            }

            .profile-card p,
            .fixed.inset-0.z-50 p {
                color: #94a3b8 !important;
                font-size: 0.85rem !important;
            }

            /* Labels and description styling */
            .profile-card label, 
            .profile-card .text-gray-750, 
            .profile-card .text-gray-800 {
                color: #cbd5e1 !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 500 !important;
                font-size: 0.875rem !important;
            }

            /* Text Inputs */
            .profile-card input[type="text"],
            .profile-card input[type="email"],
            .profile-card input[type="password"],
            .fixed.inset-0.z-50 input[type="text"],
            .fixed.inset-0.z-50 input[type="email"],
            .fixed.inset-0.z-50 input[type="password"] {
                background-color: #0f0f15 !important;
                border: 1px solid rgba(255, 255, 255, 0.12) !important;
                color: #f1f5f9 !important;
                border-radius: 10px !important;
                padding: 0.625rem 0.875rem !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 0.875rem !important;
                transition: all 0.2s ease !important;
                width: 100% !important;
                box-shadow: none !important;
            }

            .profile-card input[type="text"]:focus,
            .profile-card input[type="email"]:focus,
            .profile-card input[type="password"]:focus,
            .fixed.inset-0.z-50 input[type="password"]:focus {
                border-color: #4f46e5 !important;
                outline: none !important;
                box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2) !important;
            }

            /* Button overrides */
            .profile-card button[type="submit"]:not(.text-red-600):not(.bg-red-600):not(.bg-red-500) {
                background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
                color: #ffffff !important;
                border: none !important;
                border-radius: 9999px !important;
                padding: 0.6rem 1.75rem !important;
                font-weight: 700 !important;
                font-size: 0.8rem !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer !important;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3) !important;
            }

            .profile-card button[type="submit"]:not(.text-red-600):not(.bg-red-600):not(.bg-red-500):hover {
                transform: translateY(-1px) scale(1.02);
                box-shadow: 0 6px 18px rgba(79, 70, 229, 0.45) !important;
            }

            .profile-card button[type="submit"]:not(.text-red-600):not(.bg-red-600):not(.bg-red-500):active {
                transform: translateY(0) scale(0.97);
            }

            /* Red danger button */
            .profile-card button.bg-red-600,
            .profile-card button.bg-red-600:hover,
            .profile-card button.bg-red-500,
            .profile-card button.bg-red-500:hover,
            .fixed.inset-0.z-50 button.bg-red-600,
            .fixed.inset-0.z-50 button.bg-red-600:hover {
                background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%) !important;
                color: #ffffff !important;
                border: none !important;
                border-radius: 9999px !important;
                padding: 0.6rem 1.75rem !important;
                font-weight: 700 !important;
                font-size: 0.8rem !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                box-shadow: 0 4px 15px rgba(225, 29, 72, 0.3) !important;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            }

            .profile-card button.bg-red-600:hover,
            .profile-card button.bg-red-500:hover,
            .fixed.inset-0.z-50 button.bg-red-600:hover {
                transform: translateY(-1px) scale(1.02);
                box-shadow: 0 6px 18px rgba(225, 29, 72, 0.45) !important;
            }

            /* Modal background dark mode styling */
            .fixed.inset-0.z-50 {
                font-family: 'Outfit', sans-serif !important;
            }

            .fixed.inset-0.z-50 .bg-white {
                background-color: #12121a !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
                color: #f8fafc !important;
                border-radius: 16px !important;
            }

            /* Secondary/Cancel Button inside modal */
            .fixed.inset-0.z-50 button.bg-white,
            .fixed.inset-0.z-50 button.border-gray-300,
            .fixed.inset-0.z-50 button:not(.bg-red-600):not(.bg-red-500) {
                background-color: #1e293b !important;
                color: #cbd5e1 !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 9999px !important;
                transition: all 0.2s ease !important;
            }

            .fixed.inset-0.z-50 button.bg-white:hover,
            .fixed.inset-0.z-50 button.border-gray-300:hover,
            .fixed.inset-0.z-50 button:not(.bg-red-600):not(.bg-red-500):hover {
                background-color: #334155 !important;
                color: #ffffff !important;
            }

            /* Saved status message styling */
            .text-gray-600,
            .profile-card p.text-sm.text-gray-600 {
                color: #10b981 !important; /* Green saved indicator */
                font-weight: 600;
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 profile-wrapper">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 profile-card shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 profile-card shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 profile-card shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
