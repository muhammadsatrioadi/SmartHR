<form method="POST" action="{{ route('language.update') }}" class="language-switch-form">
    @csrf
    <div class="language-switch" role="radiogroup" aria-label="{{ __('Ganti bahasa') }}">
        <input type="radio" id="portal-locale-id" name="locale" value="id" {{ app()->getLocale() === 'id' ? 'checked' : '' }} onchange="this.form.submit()">
        <label for="portal-locale-id">ID</label>
        <input type="radio" id="portal-locale-en" name="locale" value="en" {{ app()->getLocale() === 'en' ? 'checked' : '' }} onchange="this.form.submit()">
        <label for="portal-locale-en">EN</label>
        <span class="language-switch__thumb" aria-hidden="true"></span>
    </div>
</form>
