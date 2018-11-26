@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Findemi
        @endcomponent
    @endslot
    {{-- Body --}}
    Thanks for Register to Findemi App! Please before you begin, you must confirm your account.
    Confirm Account {{ $url }}
    Thank you for using our findemi!
    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. Findemi
        @endcomponent
    @endslot
@endcomponent