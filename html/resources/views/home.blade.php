@extends('layouts.app')

@section('content')

<div class="container py-5">

    {{-- HERO CON CARRUSEL --}}
    <div id="" class="landing-hero-wrapper mb-5">
        <div class="row align-items-center g-4">
            {{-- Columna izquierda: texto principal --}}
            <div class="col-lg-6 position-relative">
                <div class="landing-pill mb-3">
                    <span></span>
                    <small>Traslados en isla · 24/7</small>
                </div>

                <h1 class="display-5 fw-bold mb-3">
                    Traslados en isla
                    <span class="d-block">sin colas ni sorpresas.</span>
                </h1>

                <p class="lead mb-4">
                    Reserva tu traslado aeropuerto ↔ hotel en segundos
                    y lleva todas tus reservas en un único panel.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4 hero-badges">
                    <span class="badge badge-service">
                        ✈️ Aeropuerto ↔ hotel
                    </span>
                    <span class="badge badge-service">
                        🚐 Flota seleccionada en la isla
                    </span>
                    <span class="badge badge-panel">
                        👥 Paneles para hoteles y viajeros
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2">
    <a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
                ? route('transfer.select-type')
                : route('login') }}"
       class="btn btn-teal">
        Reservar traslado
    </a>

    <a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
                ? route('dashboard')
                : route('login') }}"
       class="btn btn-soft-yellow">
        Acceder al panel
    </a>
</div>
            </div>

            {{-- Columna derecha: carrusel --}}
            <div class="col-lg-6 hero-right-col">
                <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"
                                aria-current="true" aria-label="Traslados"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"
                                aria-label="Hoteles"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"
                                aria-label="Viajeros"></button>
                    </div>

                    <div class="carousel-inner">
                        {{-- Slide 1: Traslados --}}
                        <div class="carousel-item active">
                            <div class="hero-slide-content">
                                <h3 class="h5 fw-bold mb-2">
                                    Traslados rápidos aeropuerto ↔ hotel
                                </h3>
                                <p class="mb-3 text-muted">
                                    Elige origen y destino, horario y tipo de vehículo. Nosotros conectamos tu reserva
                                    con la flota disponible en la isla.
                                </p>
                                <ul class="mb-3 small text-muted">
                                    <li>Confirmación inmediata y resumen por pantalla.</li>
                                    <li>Vehículos adaptados al número de viajeros.</li>
                                    <li>Sin llamadas ni correos: todo desde la web.</li>
                                </ul>
                                {{-- <a href="{{ route('transfer.select-type') }}" class="btn btn-teal btn-sm">
                                    Empezar reserva
                                </a> --}}
                            </div>
                        </div>

                        {{-- Slide 2: Panel hoteles --}}
                        <div class="carousel-item">
                            <div class="hero-slide-content">
                                <h3 class="h5 fw-bold mb-2">Panel para hoteles</h3>
                                <p class="mb-3 text-muted">
                                    Gestiona reservas de los huéspedes, visualiza comisiones y controla los traslados
                                    del alojamiento en un solo lugar.
                                </p>
                                <ul class="mb-3 small text-muted">
                                    <li>Reservas rápidas para huéspedes.</li>
                                    <li>Comisiones claras y mensuales.</li>
                                    <li>Acceso multiusuario del personal.</li>
                                </ul>
                                {{-- <a href="{{ route('register') }}" class="btn btn-soft-yellow btn-sm">
                                    Soy un hotel, quiero registrarme
                                </a> --}}
                            </div>
                        </div>

                        {{-- Slide 3: Panel viajeros --}}
                        <div class="carousel-item">
                            <div class="hero-slide-content">
                                <h3 class="h5 fw-bold mb-2">
                                    Viajeros con todo bajo control
                                </h3>
                                <p class="mb-3 text-muted">
                                    Desde tu área privada puedes revisar tus reservas, modificar datos y ver horarios
                                    sin depender de terceros.
                                </p>
                                <ul class="mb-3 small text-muted">
                                    <li>Histórico de reservas siempre disponible.</li>
                                    <li>Datos claros del punto de recogida y destino.</li>
                                    <li>Diseñada para usar desde móvil en pleno viaje.</li>
                                </ul>
                                {{-- <a href="{{ route('register') }}" class="btn btn-teal btn-sm">
                                    Crear cuenta viajero
                                </a> --}}
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: ELIGE TU TRASLADO --}}
    <section id="transfers" class="transfer-options">
        <div class="transfer-options-header">
            <h2 class="landing-section-title h4 mb-2">
                Elige tu traslado en la isla
            </h2>
            <p class="text-muted mb-0">
                Tres formas de moverte entre aeropuerto y hotel. Reserva en segundos,
                sin llamadas ni correos.
            </p>
        </div>

        <div class="row g-4">
            {{-- Card 1: Aeropuerto → hotel --}}
            <div class="col-md-4">
                <a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
            ? route('transfer.select-type')
            : route('login') }}"
   class="transfer-card">
                    <div class="transfer-card-icon">🛬</div>
                    <h3>Aeropuerto → hotel</h3>
                    <p>
                        Te recogemos a la llegada y te llevamos directo a tu alojamiento,
                        sin esperas en el aeropuerto.
                    </p>
                    <span class="transfer-card-meta">
                        Ideal para llegadas con equipaje o en grupo.
                    </span>
                    <span class="transfer-card-cta">
                        Reservar este trayecto →
                    </span>
                </a>
            </div>

            {{-- Card 2: Hotel → aeropuerto --}}
            <div class="col-md-4">
                <a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
                        ? route('transfer.select-type')
                        : route('login') }}"
               class="transfer-card">
                    <div class="transfer-card-icon">🛫</div>
                    <h3>Hotel → aeropuerto</h3>
                    <p>
                        Marca la hora de salida y nos encargamos de que llegues con tiempo
                        a tu vuelo.
                    </p>
                    <span class="transfer-card-meta">
                        Sin prisas de última hora ni taxis improvisados.
                    </span>
                    <span class="transfer-card-cta">
                        Planificar salida →
                    </span>
                </a>
            </div>

            {{-- Card 3: Ida y vuelta --}}
            <div class="col-md-4">
                <a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
                        ? route('transfer.select-type')
                        : route('login') }}"
               class="transfer-card">
                    <div class="transfer-card-icon">🔁</div>
                    <h3>Ida y vuelta</h3>
                    <p>
                        Deja cerrados los dos traslados: llegada y salida organizadas
                        desde el primer momento.
                    </p>
                    <span class="transfer-card-meta">
                        La opción más cómoda para estancias completas.
                    </span>
                    <span class="transfer-card-cta">
                        Reservar ida y vuelta →
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- SECCIÓN: BENEFICIOS (LISTA SIMPLE) --}}
    <section class="benefits-section">
        <div class="benefits-band">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="benefits-header">
                        <h2 class="landing-section-title h4 mb-2">
                            ¿Por qué reservar con Isla Transfers?
                        </h2>
                        <p class="mb-0">
                            Una plataforma pensada para que tu llegada y salida de la isla
                            sean tan sencillas como tu reserva.
                        </p>
                    </div>

                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon-round">⏱️</div>
                            <div>
                                <p class="benefit-item-title">Sin colas ni esperas</p>
                                <p class="benefit-item-text">
                                    Reserva antes de viajar y súbete directamente al vehículo al llegar.
                                </p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon-round">💶</div>
                            <div>
                                <p class="benefit-item-title">Precio cerrado</p>
                                <p class="benefit-item-text">
                                    Conoce el importe antes de confirmar, sin sorpresas ni recargos ocultos.
                                </p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon-round">🧑‍✈️</div>
                            <div>
                                <p class="benefit-item-title">Conductores verificados</p>
                                <p class="benefit-item-text">
                                    Flota seleccionada para que cada traslado sea seguro y cómodo.
                                </p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon-round">📱</div>
                            <div>
                                <p class="benefit-item-title">Todo desde el móvil</p>
                                <p class="benefit-item-text">
                                    Consulta horarios, puntos de recogida y reservas desde tu área privada.
                                </p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon-round">🏨</div>
                            <div>
                                <p class="benefit-item-title">Hoteles conectados</p>
                                <p class="benefit-item-text">
                                    Panel propio para que los alojamientos gestionen los traslados de sus huéspedes.
                                </p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon-round">📊</div>
                            <div>
                                <p class="benefit-item-title">Visión global de la isla</p>
                                <p class="benefit-item-text">
                                    Reservas organizadas por zonas para analizar mejor la demanda de traslados.
                                </p>
                            </div>
                        </div>
                    </div> {{-- .benefits-list --}}
                </div>
            </div>
        </div>
    </section>


    {{-- SECCIÓN FLOTA: ACORDEÓN HORIZONTAL --}}
    <section id="fleet" class="fleet-section container py-5">
        <div class="text-center mb-4">
            <h2 class="landing-section-title h4 mb-2">
                Vehículos preparados para tus traslados
            </h2>
            <p class="text-muted mb-0">
                Desde sedanes cómodos hasta minivans VIP, elige el vehículo que mejor encaje
                con tu llegada o salida en la isla.
            </p>
        </div>

        <div class="fleet-accordion">
            {{-- 1. Sedán Demo --}}
            <article class="fleet-item fleet-item-1">
                <div class="fleet-overlay">
                    <h3>Sedán Demo</h3>
                    <p>Cómodo y elegante para traslados diarios.</p>
                    <ul>
                        <li>👥 Hasta 3 pasajeros</li>
                        <li>🧳 2 maletas grandes</li>
                    </ul>
                    {{-- <span class="fleet-tag">Ideal para traslados individuales o en pareja.</span> --}}
                </div>
            </article>

            {{-- 2. Minivan VIP Deluxe --}}
            <article class="fleet-item fleet-item-2">
                <div class="fleet-overlay">
                    <h3>Minivan VIP Deluxe</h3>
                    <p>Perfecta para familias y grupos pequeños.</p>
                    <ul>
                        <li>👥 Hasta 7 pasajeros</li>
                        <li>🧳 4-5 maletas</li>
                    </ul>
                    {{-- <span class="fleet-tag">Espacio y comodidad para todos.</span> --}}
                </div>
            </article>

            {{-- 3. Toyota Yaris --}}
            <article class="fleet-item fleet-item-3">
                <div class="fleet-overlay">
                    <h3>Toyota Yaris</h3>
                    <p>Compacto, ágil y muy eficiente.</p>
                    <ul>
                        <li>👥 Hasta 3 pasajeros</li>
                        <li>🧳 2 maletas de cabina</li>
                    </ul>
                    {{-- <span class="fleet-tag">Genial para moverse rápido por la isla.</span> --}}
                </div>
            </article>

            {{-- 4. Mustang --}}
            <article class="fleet-item fleet-item-4">
                <div class="fleet-overlay">
                    <h3>Mustang</h3>
                    <p>Estilo deportivo para una experiencia única.</p>
                    <ul>
                        <li>👥 Hasta 2 pasajeros</li>
                        <li>🧳 Equipaje ligero</li>
                    </ul>
                    {{-- <span class="fleet-tag">Para quienes quieren algo diferente.</span> --}}
                </div>
            </article>

            {{-- 5. Milano Starship --}}
            <article class="fleet-item fleet-item-5">
                <div class="fleet-overlay">
                    <h3>Milano Starship</h3>
                    <p>SUV premium para viajar con total confort.</p>
                    <ul>
                        <li>👥 Hasta 4 pasajeros</li>
                        <li>🧳 3-4 maletas</li>
                    </ul>
                    {{-- <span class="fleet-tag">La opción más exclusiva de la flota.</span> --}}
                </div>
            </article>
        </div>
    </section>



    {{-- SECCIÓN: TESTIMONIOS --}}
    <section class="testimonials-section">
        <div class="testimonials-header">
            <h2 class="landing-section-title h4 mb-2">
                Lo que dicen nuestros clientes
            </h2>
            <p class="mb-0">
                Hoteles y viajeros que ya usan Isla Transfers para organizar sus traslados en la isla.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-user">
                        <div class="testimonial-avatar">CA</div>
                        <div>
                            <div class="testimonial-name">Hotel Cala Azul</div>
                            <div class="testimonial-role">Recepción</div>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        “Antes gestionábamos los traslados con llamadas y correos. Ahora todo está en un solo panel
                        y sabemos las comisiones de cada mes al momento.”
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-user">
                        <div class="testimonial-avatar">LM</div>
                        <div>
                            <div class="testimonial-name">Laura M.</div>
                            <div class="testimonial-role">Viajera</div>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        “Reservé ida y vuelta en cinco minutos. Al llegar ya nos estaban esperando y no tuvimos que
                        buscar taxi después del vuelo.”
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-user">
                        <div class="testimonial-avatar">IT</div>
                        <div>
                            <div class="testimonial-name">Isla Transfers</div>
                            <div class="testimonial-role">Equipo de operaciones</div>
                        </div>
                    </div>
                    <p class="testimonial-text">
                        “Diseñamos la plataforma para que la coordinación entre hoteles, viajeros y administración
                        sea clara, rápida y sin sorpresas.”
                    </p>
                </div>
            </div>
        </div>
    </section>

</div> {{-- cierre del container principal --}}

@endsection

@section('footer')

    <footer class="site-footer">
        <div class="container">
            <div class="row gy-4">
                {{-- Columna 1 --}}
                <div class="col-md-4">
                    <div class="site-footer-title">
                        ISLA TRANSFERS
                    </div>
                    <p class="site-footer-text mb-0">
                        Traslados aeropuerto ↔ hotel en la isla,
                        con paneles para administradores, hoteles y viajeros.
                    </p>
                </div>

                {{-- Columna 2 --}}
                <div class="col-md-4">
                    <div class="site-footer-heading">
                        Navegación
                    </div>
                    <a href="{{ route('home') }}" class="site-footer-link">
    <span>➜</span> Inicio
</a>

<a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
            ? route('transfer.select-type')
            : route('login') }}"
   class="site-footer-link">
    <span>➜</span> Reservar traslado
</a>

<a href="{{ auth('admin')->check() || auth('corporate')->check() || auth('web')->check()
            ? route('dashboard')
            : route('login') }}"
   class="site-footer-link">
    <span>➜</span> Acceder al panel
</a>
                </div>

                {{-- Columna 3 --}}
                <div class="col-md-4">
                    <div class="site-footer-heading">
                        En una frase
                    </div>
                    <p class="site-footer-highlights mb-0">
                        24/7 en la isla, precio cerrado y paneles
                        para hoteles y viajeros en una misma plataforma.
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center site-footer-bottom mt-4">
                <span>© {{ date('Y') }} Isla Transfers. Todos los derechos reservados.</span>

            <div class="footer-socials">
                <a href="#" class="footer-social">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325
                                24h11.495v-9.294H9.691V11.01h3.129V8.414c0-3.1 1.893-4.788
                                4.659-4.788 1.325 0 2.463.099 2.794.143v3.24l-1.918.001c-1.504
                                0-1.796.715-1.796 1.763v2.316h3.587l-.467 3.696h-3.12V24h6.116C23.406
                                24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z"/>
                    </svg>
                </a>

                <a href="#" class="footer-social">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25
                                22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75
                                2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75
                                20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25
                                3.5h-8.5zM12 7a5 5 0 1 1 0 10a5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0
                                7a3.5 3.5 0 0 0 0-7zm5.25-.25a1.25 1.25 0 1 1 0-2.5a1.25 1.25 0 0
                                1 0 2.5z"/>
                    </svg>
                </a>


               <a href="#" class="footer-social">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M18.244 2H21L14.48 10.01L22 22h-6.56l-4.57-6.94L5.06
                                22H2l7.05-8.63L2 2h6.72l4.13 6.27L18.244 2zm-2.37
                                17.31h1.3L7.41 4.62H6.02l9.854 14.69z"/>
                    </svg>
                </a>

            </div>

            <div class="d-flex gap-3">
                <a href="#" class="small">Términos y condiciones</a>
                <a href="#" class="small">Política de privacidad</a>
            </div>
        </div>
    </footer>

@endsection
