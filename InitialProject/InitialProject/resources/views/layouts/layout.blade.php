<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trans('expertise.title') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">{{ trans('expertise.home') }}</a></li>
                <li><a href="{{ route('expertise') }}">{{ trans('expertise.expertise') }}</a></li>
                <li>
                    <form method="GET" action="" id="languageForm">
                        <select name="lang" id="languageSwitcher" class="form-select">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                            <option value="th" {{ app()->getLocale() == 'th' ? 'selected' : '' }}>ไทย</option>
                            <option value="cn" {{ app()->getLocale() == 'cn' ? 'selected' : '' }}>中文</option>
                        </select>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('languageSwitcher').addEventListener('change', function() {
            let lang = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('lang', lang);
            window.location.href = url.toString();
        });
    </script>
</body>
</html>