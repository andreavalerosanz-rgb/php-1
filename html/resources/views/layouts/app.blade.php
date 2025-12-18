<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Producto 3 - Traslados Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

    <style>

       /* =========================
        NAVBAR LANDING
        ========================== */
        .landing-navbar {
            background: rgba(34, 85, 91, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.35);
        }

        /* Badge redondo del logo */
        .brand-badge {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            background: radial-gradient(circle at 0% 0%, #facc6b, #0f766e);
            color: #0b1120;
        }

        /* Links del navbar */
        .landing-navbar .nav-link {
            position: relative;
            padding-bottom: 4px;
            color: #e5f9f7;
            opacity: .85;
            transition: color .25s ease, opacity .25s ease;
        }

        /* Base del subrayado (oculto por defecto) */
        .landing-navbar .nav-link::after {
            content: "";
            position: absolute;
            left: 0.6rem;
            right: 0.6rem;
            bottom: -0.35rem;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #0f766e, #facc6b);
            transform: scaleX(0);
            transform-origin: center;
            opacity: 0;
            transition: transform .25s ease-out, opacity .25s ease-out;
        }

        /* Hover */
        .landing-navbar .nav-link:hover {
            opacity: 1;
            color: #facc6b;
        }

        .landing-navbar .nav-link:hover::after {
            opacity: 1;
            transform: scaleX(1);
        }

        /* Activo */
        .landing-navbar .nav-link.active {
            opacity: 1;
            color: #facc6b;
        }

        .landing-navbar .nav-link.active::after {
            opacity: 1;
            transform: scaleX(1);
        }
        /* =========================
            DROPDOWN PERFIL (NAVBAR)
        ========================== */
        .nav-profile {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        padding: .35rem .55rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.10);
        color: #e5f9f7;
        font-weight: 700;
        text-decoration: none;
        transition: .15s ease;
        }

        .nav-profile:hover {
        background: rgba(255,255,255,.16);
        color: #facc6b;
        }



        .nav-profile .caret {
        opacity: .8;
        }

        .dropdown-menu.profile-menu {
        border: none;
        border-radius: 14px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .18);
        padding: .4rem;
        min-width: 210px;
        }

        .dropdown-menu.profile-menu .dropdown-item {
        border-radius: 10px;
        padding: .55rem .7rem;
        font-weight: 700;
        color: #0f172a;
        }

        .dropdown-menu.profile-menu .dropdown-item:hover {
        background: #ccfbf1;
        color: #0f766e;
        }

        .dropdown-menu.profile-menu .dropdown-divider {
        margin: .35rem 0;
        }

        .profile-logout {
        width: 100%;
        text-align: left;
        background: transparent;
        border: none;
        padding: .55rem .7rem;
        border-radius: 10px;
        font-weight: 800;
        color: #b91c1c;
        }

        .profile-logout:hover {
        background: #fee2e2;
        }
        .nav-avatar{
        width: 34px;
        height: 34px;
        border-radius: 999px;
        background: #facc6b;
        color: #0f766e;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        }

        .nav-avatar svg{
        width: 18px;
        height: 18px;
        display:block;
        


        /* Móvil: centramos menú y opcionalmente quitamos subrayado si molesta */
        @media (max-width: 991.98px) {
            .landing-navbar .navbar-nav {
                margin-top: 1rem;
                text-align: center;
            }

            /* Si quieres quitar rayita en móvil, deja esto;
            si quieres que siga saliendo, elimina este bloque */
            .landing-navbar .nav-link::after {
                display: none;
            }
        }


    </style>
</head>
<body>

@php
    use Illuminate\Support\Facades\Auth;

    $isAdmin     = Auth::guard('admin')->check();
    $isCorporate = Auth::guard('corporate')->check();
    $isTraveler  = Auth::guard('web')->check();

    $userName = null;

    if ($isAdmin) {
        $userName = Auth::guard('admin')->user()->nombre ?? 'Admin';
    } elseif ($isCorporate) {
        $userName = Auth::guard('corporate')->user()->nombre ?? 'Hotel';
    } elseif ($isTraveler) {
        $userName = Auth::guard('web')->user()->nombre ?? 'Usuario';
    }

    $initials = $userName ? strtoupper(mb_substr($userName, 0, 1)) : 'U';
@endphp

<nav class="navbar navbar-expand-lg"
     style="background:#0f766e; padding: .65rem 0;">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center text-white"
           href="{{ route('home') }}#hero">
            <div class="rounded-circle me-2 d-flex justify-content-center align-items-center"
                 style="width:34px; height:34px; background:#facc6b; color:#0f766e; font-weight:700;">
                IT
            </div>
            <span class="fw-semibold">Isla Transfers P3</span>
        </a>

        {{-- Botón hamburguesa --}}
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

        {{-- Links centrados --}}
        <ul class="navbar-nav mx-auto gap-lg-3">

            {{-- ADMIN --}}
            @if ($isAdmin)
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('/') ? 'active' : '' }}"
                    href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.vehiculos.*') ? 'active' : '' }}"
                    href="{{ route('admin.vehiculos.index') }}">
                        Vehículos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('admin.reservations.list') ? 'active' : '' }}"
                    href="{{ route('admin.reservations.list') }}">
                        Traslados
                    </a>
                </li>

            {{-- HOTEL (corporate) --}}
            @elseif ($isCorporate)
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('/') ? 'active' : '' }}"
                    href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('transfer.select-type') ? 'active' : '' }}"
                    href="{{ route('transfer.select-type') }}">
                        Traslados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('corporate.comissions') ? 'active' : '' }}"
                    href="{{ route('corporate.comissions') }}">
                        Comisiones
                    </a>
                </li>

            {{-- VIAJERO (web) --}}
            @elseif ($isTraveler)
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('/') ? 'active' : '' }}"
                    href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('mis_reservas') ? 'active' : '' }}"
                    href="{{ route('mis_reservas') }}">
                        Mis reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('transfer.select-type') ? 'active' : '' }}"
                    href="{{ route('transfer.select-type') }}">
                        Traslados
                    </a>
                </li>

            {{-- VISITANTE --}}
            @else
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('/') ? 'active' : '' }}"
                    href="{{ route('home') }}#hero" data-scroll="true">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white"
                    href="{{ route('home') }}#transfers" data-scroll="true">
                        Traslados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white"
                    href="{{ route('home') }}#fleet" data-scroll="true">
                        Vehículos
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('transfer.select-type') ? 'active' : '' }}"
                    href="{{ route('transfer.select-type') }}">
                        Traslados
                    </a>
                </li> --}}
            @endif

</ul>


            {{-- Botones derecha --}}
            <div class="d-flex gap-2 align-items-center">

                {{-- VISITANTE --}}
                @if (!$isAdmin && !$isCorporate && !$isTraveler)
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-sm"
                    style="background:#facc6b; color:#0f766e; font-weight:600;">
                        Registro
                    </a>

                {{-- USUARIO LOGUEADO --}}
                @else

                    {{-- BOTÓN PANEL (siempre visible) --}}
                    @if ($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">
                            Panel
                        </a>
                    @elseif ($isCorporate)
                        <a href="{{ route('corporate.dashboard') }}" class="btn btn-sm btn-light">
                            Panel
                        </a>
                    @elseif ($isTraveler)
                        <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-light">
                            Panel
                        </a>
                    @endif

                    {{-- DROPDOWN PERFIL --}}
                    <div class="dropdown">
                        <a class="nav-profile dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                            <span class="nav-avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 20.25a7.5 7.5 0 0115 0"/>
                                </svg>
                            </span>


                            <span class="d-none d-md-inline">{{ $userName }}</span>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-end profile-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Perfil
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="profile-logout">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                @endif

            </div>



        </div>
    </div>
</nav>


<main class="container mt-4">
    @yield('content')
</main>

@yield('footer')



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('a[data-scroll="true"]');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const hashIndex = href.indexOf('#');
            if (hashIndex === -1) return; // no hay ancla

            const targetId = href.substring(hashIndex + 1);
            const targetEl = document.getElementById(targetId);

            if (targetEl) {
                e.preventDefault();
                window.scrollTo({
                    top: targetEl.offsetTop - 80, // margen por el navbar
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>

</body>
</html>
