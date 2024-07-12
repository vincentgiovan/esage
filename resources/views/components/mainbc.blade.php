<span {{ $attributes->merge(["class" => ""]) }}>
    <span><a href="{{ route("dashboard") }}">Home</a></span>
    {{ $slot }}
</span>
