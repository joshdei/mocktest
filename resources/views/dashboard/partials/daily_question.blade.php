{{-- 3. QUESTION OF THE DAY (STATE BLOCK) --}}
@php
    $isVerified = auth()->user()?->hasVerifiedEmail();
@endphp

@if(! $isVerified)
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 mb-4">
        <div class="flex items-start gap-3">
            <div class="text-amber-600 text-xl">✉️</div>
            <div class="flex-1">
                <div class="font-semibold text-amber-800">Verify Your Email</div>
                <div class="text-amber-800/80 text-sm mt-1">Verify your email to unlock the Question of the Day and earn ₦10</div>
                <div class="mt-3">
                <a href="{{ route('verification.notice') }}" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-white text-sm font-semibold">
                        Resend Verification Email
                    </a>
                @php($attempt= $attempt)
                </div>
            </div>
        </div>
    </div>
@elseif(!($questionVisible ?? false))
    <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 mb-4">
        <div class="flex items-start gap-3">
            <div class="text-amber-700 text-xl">🔒</div>
            <div class="flex-1">
                <div class="font-semibold text-amber-900">No question today — check back this week</div>
                <div class="text-amber-900/70 text-sm mt-1">Once per week, a new Question of the Day is scheduled.</div>
            </div>
        </div>
    </div>
@elseif(($alreadyAnswered ?? false) === true)
    <div class="rounded-xl p-4 mb-4 border {{ ($attempt->is_correct ?? false) ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }}">
        <div class="flex items-start gap-3">
            <div class="text-{{ ($attempt->is_correct ?? false) ? 'emerald' : 'red' }}-700 text-xl">
                {{ ($attempt->is_correct ?? false) ? '✅' : '❌' }}
            </div>
            <div class="flex-1">
                <div class="font-semibold text-{{ ($attempt->is_correct ?? false) ? 'emerald' : 'red' }}-900">
                    {{ ($attempt->is_correct ?? false) ? 'Correct! ₦10 has been credited to your wallet' : 'Wrong answer. Better luck next week!' }}
                </div>
                <div class="text-sm mt-2 text-slate-700">
                    Correct Answer: <span class="font-semibold">{{ $schedule->question->answer ?? '' }}</span>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="rounded-xl border border-amber-200 bg-white p-4 mb-4 shadow-sm">
        <form method="POST" action="{{ route('daily.question.submit') }}">
            @csrf
            <div class="flex items-start gap-3">
                <div class="text-amber-700 text-xl">🎯</div>
                <div class="flex-1">
                    <div class="font-semibold text-amber-900">Question of the Day</div>
                    <div class="text-sm text-slate-700 mt-1">Answer correctly to earn ₦10</div>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-slate-900 font-semibold">{{ $schedule->question->question }}</div>
            </div>

            @php $qt = $schedule->question->question_type; @endphp

            @if($qt === 'mcq')
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach([
                        'a' => $schedule->question->option_a,
                        'b' => $schedule->question->option_b,
                        'c' => $schedule->question->option_c,
                        'd' => $schedule->question->option_d,
                    ] as $letter => $text)
                        <label class="block cursor-pointer">
                            <input type="radio" name="selected_option" value="{{ $letter }}" class="hidden peer" required>
                            <div class="px-4 py-3 rounded-lg border border-amber-200 bg-amber-50 peer-checked:border-amber-700 peer-checked:bg-amber-100 transition">
                                <div class="flex items-center gap-2">
                                    <div class="font-bold text-amber-800">{{ strtoupper($letter) }}</div>
                                    <div class="text-sm text-slate-800">{{ $text }}</div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="mt-4">
                    <input
                        type="text"
                        name="selected_option"
                        value="{{ old('selected_option') }}"
                        placeholder="Type your answer..."
                        class="w-full rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-600"
                        required
                    >
                </div>
            @endif

            <div class="mt-5">
                <button type="submit" class="w-full rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-3 text-sm">
                    Submit Answer
                </button>
            </div>
        </form>
    </div>
@endif

