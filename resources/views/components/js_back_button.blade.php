<input
    type="image"
    src="{{ Vite::asset('resources/imgs/back.png') }}";
    class="backbutton buttons"
    onclick="document.referrer ? window.location = document.referrer : history.back();"
/>
