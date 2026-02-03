@props(['route'])
<a href="{{ route($route) }}" class="{{ config('config.sampleForm.buttonCreate') }} mt-2 bg-theme">
    <button type="button" class="">
        <i class="fa-solid fa-backward me-4"></i>Go To List
    </button>
</a>
