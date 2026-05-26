{{-- resources/views/popup/emailverified.blade.php --}}

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="background: rgba(0,0,0,0.75);"
>
    <div class="relative bg-[#0a0f1c] border border-green-600/40 rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden">

        {{-- Close Button --}}
        <button
            @click="show = false"
            class="absolute top-3 right-3 text-gray-400 hover:text-white transition z-10"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Flyer Image --}}
        <div class="w-full">
            <img
                src="{{ asset('images/verify-email-flyer.png') }}"
                alt="Verify Your Email"
                class="w-full object-cover rounded-t-2xl"
            />
        </div>

        {{-- Content --}}
        <div class="px-6 py-5 text-center">
            <h2 class="text-white text-lg font-bold mb-1">Verify Your Email</h2>
            <p class="text-gray-400 text-sm mb-5">
                Confirm your email address and earn <span class="text-green-400 font-semibold">5 bonus points</span> toward your exam journey.
            </p>

            {{-- Resend Verification Email --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-500 text-white font-semibold py-3 rounded-xl transition duration-200 text-sm tracking-wide"
                >
                    Check Your Inbox / Resend Link →
                </button>
            </form>

            <button
                @click="show = false"
                class="mt-3 text-xs text-gray-500 hover:text-gray-300 transition"
            >
                Maybe later
            </button>
        </div>
    </div>
</div>