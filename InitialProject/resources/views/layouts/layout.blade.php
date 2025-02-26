<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบข้อมูลงานวิจัย วิทยาลัยการคอมพิวเตอร์</title>
    <base href="{{ \URL::to('/') }}">
    <link href="img/Newlogo.png" rel="shortcut icon" type="image/x-icon" />

    <!-- Stylesheets -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/load-more-button.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/typicons/typicons.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.css" />

    <!-- Language Flags -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

</head>

<body>
    <!-- Navigation -->
    <nav id="navbar" class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            @php
                $locale = app()->getLocale();
                $logoPath = "/img/en/logo2.png";
                if ($locale == 'th') $logoPath = "/img/th/logo2.png";
                elseif ($locale == 'cn') $logoPath = "/img/cn/logo2.png";
            @endphp
            <a class="navbar-brand logo-image" href="#">
                <img src="{{ asset($logoPath) }}" alt="Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ms-auto navbar-nav-scroll">
                    <li class="nav-item {{ request()->is('/') ? 'active' : ''}}">
                        <a class="nav-link" href="/">{{ trans('message.Home') }}</a>
                    </li>
                    <li class="nav-item dropdown {{ Request::routeIs('researchers') ? 'active' : '' }} {{ request()->is('detail*') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ trans('message.Researchers') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @foreach($dn as $department)
                            <li>
                                <a class="dropdown-item" href="{{ route('researchers',['id'=>$department->id])}}">
                                    @if ($locale == 'th')
                                        {{ $department->program_name_th }}
                                    @elseif ($locale == 'cn')
                                        {{ $department->program_name_cn }}
                                    @else
                                        {{ $department->program_name_en }}
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->is('researchproject') ? 'active' : ''}}">
                        <a class="nav-link" href="/researchproject">{{ trans('message.ResearchProj') }}</a>
                    </li>
                    <li class="nav-item {{ request()->is('researchgroup') ? 'active' : ''}}">
                        <a class="nav-link" href="/researchgroup">{{ trans('message.ResearchGroup') }}</a>
                    </li>
                    <li class="nav-item {{ request()->is('reports') ? 'active' : ''}}">
                        <a class="nav-link" href="/reports">{{ trans('message.Report') }}</a>
                    </li>

                    <!-- Language Switch -->
                    <li class="nav-item">
                        <span class="nav-link">
                            <strong>{{ Config::get('languages')[App::getLocale()]['display'] }}</strong> |
                            @foreach (Config::get('languages') as $lang => $language)
                                @if ($lang != App::getLocale())
                                    <a class="text-decoration-none" href="{{ route('langswitch', $lang) }}">
                                        {{ $language['display'] }}
                                    </a> |
                                @endif
                            @endforeach
                        </span>
                    </li>
                </ul>

                @if (Route::has('login'))
                    @auth
                        <span class="nav-item"></span>
                    @else
                        <span class="nav-item">
                            <a class="btn-solid-sm" href="/login" target="_blank">{{ trans('message.login') }}</a>
                        </span>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Content -->
    @yield('content')
    @yield('javascript')

    <!-- Footer -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between"></div>
    </footer>
</body>

</html>
