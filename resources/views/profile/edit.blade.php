<x-app-layout>
    <style>
        /* Global styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* A more modern font */
            line-height: 1.6;
            color: #333;
            background-color: #f0f2f5; /* Lighter, modern background */
            margin-left: 250px; /* Keep for desktop, adjust for mobile */
        }

        /* Section styles */
        .space-y-6 {
            padding: 2rem;
            background-color: #fff;
            border: 1px solid #e0e0e0; /* Softer border */
            border-radius: 0.75rem; /* Slightly more rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Subtle shadow for depth */
            margin-bottom: 1.5rem; /* Add some space between sections */
        }

        /* Header styles */
        header {
            margin-bottom: 1.5rem;
            text-align: center; /* Center header text */
        }

        header h2 {
            font-size: 1.8rem; /* Slightly larger heading */
            font-weight: 700; /* Bolder font weight */
            color: #2c3e50; /* Darker, more prominent color */
            margin-bottom: 0.75rem;
        }

        header p {
            font-size: 1rem; /* Slightly larger paragraph text */
            color: #7f8c8d; /* Softer grey */
            margin-bottom: 1rem;
        }

        /* Button styles */
        .danger-button,
        .secondary-button {
            padding: 0.75rem 1.5rem; /* More padding for better touch targets */
            font-size: 1rem; /* Slightly larger font */
            font-weight: 600; /* Medium bold */
            border-radius: 0.5rem; /* More rounded buttons */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
            text-transform: uppercase; /* Uppercase text for buttons */
            letter-spacing: 0.5px;
        }

        .danger-button {
            background-color: #e74c3c; /* Brighter red */
            color: #fff;
            border: none;
        }

        .danger-button:hover {
            background-color: #c0392b;
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .secondary-button {
            background-color: #95a5a6; /* Softer grey for secondary */
            color: #fff;
            border: none;
        }

        .secondary-button:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.6); /* Slightly darker overlay */
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0.5s ease-in-out, opacity 0.5s ease-in-out;
            z-index: 1000; /* Ensure modal is on top */
        }

        .modal.show {
            visibility: visible;
            opacity: 1;
        }

        .modal form {
            background-color: #fff;
            padding: 2.5rem; /* More padding */
            border-radius: 0.75rem;
            width: 90%; /* Responsive width for modal */
            max-width: 500px; /* Maximum width for larger screens */
            margin: 0 auto;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* More pronounced shadow */
        }

        .modal h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .modal p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .modal .mt-6 {
            margin-top: 2rem; /* More margin */
        }

        .modal .flex {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            align-items: center;
            gap: 1rem; /* Space between buttons */
        }

        .modal .ml-3 {
            margin-left: 0; /* Remove specific margin, use gap instead */
        }

        /* Input styles */
        .input-label {
            font-size: 0.9rem; /* Slightly larger label */
            color: #555;
            margin-bottom: 0.6rem;
            display: block; /* Make label a block element for better spacing */
            font-weight: 500;
        }

        .text-input {
            padding: 0.75rem 1rem; /* More padding */
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem; /* More rounded input fields */
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .text-input:focus {
            border-color: #3498db; /* Blue highlight on focus */
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2); /* Soft shadow on focus */
        }

        .input-error {
            font-size: 0.8rem;
            color: #e74c3c;
            margin-top: 0.5rem;
        }

        /* --- Responsive Design (Mobile First) --- */

        /* Base styles for small screens (mobiles) */
        @media (max-width: 768px) {
            body {
                margin-left: 0; /* Remove fixed margin for mobile */
                padding: 1rem; /* Add some padding to the body */
            }

            .space-y-6 {
                padding: 1rem; /* Reduced padding for smaller screens */
                margin-bottom: 1rem;
            }

            header h2 {
                font-size: 1.4rem; /* Smaller heading on mobile */
            }

            header p {
                font-size: 0.9rem; /* Smaller paragraph on mobile */
            }

            .danger-button,
            .secondary-button {
                padding: 0.6rem 1.2rem; /* Smaller buttons on mobile */
                font-size: 0.9rem;
            }

            .modal form {
                padding: 1.5rem; /* Reduced modal padding */
                width: 95%; /* Take up more width on smaller screens */
            }

            .modal h2 {
                font-size: 1.3rem;
            }

            .modal p {
                font-size: 0.85rem;
            }

            .modal .flex {
                flex-direction: column; /* Stack buttons vertically on mobile */
                gap: 0.75rem; /* Adjust gap for vertical stacking */
            }

            .modal .flex .secondary-button {
                order: 2; /* Put secondary button below danger button */
            }

            .modal .flex .danger-button {
                order: 1; /* Put danger button above secondary button */
            }
        }

        /* Styles for slightly larger mobile devices or tablets */
        @media (min-width: 480px) and (max-width: 768px) {
            .space-y-6 {
                padding: 1.5rem;
            }

            .modal form {
                width: 85%;
            }
        }

        /* Styles for tablets and larger screens (overriding mobile styles) */
        @media (min-width: 769px) {
            body {
                margin-left: 250px; /* Reapply original margin for larger screens */
            }

            .space-y-6 {
                padding: 2rem;
            }

            .modal form {
                width: 400px; /* Reapply original width for larger screens */
            }
        }

    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div>
                <div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            @can("profile-delete")
            <div>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endcan
        </div>
    </div>
</x-app-layout>