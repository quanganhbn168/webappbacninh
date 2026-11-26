{{--
    Error pages with drifting clouds. Everything is inline on purpose: they must still show when
    the database, the built assets or the settings are the thing that failed.
--}}
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">
    <title>@yield('title') | WebApp Bắc Ninh</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            min-height: 100svh;
            padding: 2rem 1.25rem;
            background: #33cc99;
            color: #fff;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            text-align: center;
        }
        main { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; }
        .brand { margin-bottom: 1rem; color: #fff; font-size: 1.25rem; font-weight: 800; text-decoration: none; }
        .code { font-size: clamp(6.5rem, 28vw, 13.75rem); font-weight: 800; line-height: 1.05; letter-spacing: .06em; }
        .rule { position: relative; width: min(26rem, 80vw); height: 5px; margin-top: .5rem; background: #fff; }
        .rule::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.1rem;
            height: 1.1rem;
            border: 4px solid #33cc99;
            border-radius: 50%;
            background: #fff;
            box-sizing: content-box;
            transform: translate(-50%, -50%);
        }
        h1 { margin-top: 1.5rem; font-size: clamp(1.7rem, 6vw, 3.25rem); line-height: 1.2; text-transform: uppercase; letter-spacing: .08em; }
        p { max-width: 32rem; margin-top: .75rem; font-size: 1.2rem; font-weight: 600; line-height: 1.5; }
        .button {
            display: inline-block;
            min-width: min(22rem, 80vw);
            margin-top: 2.25rem;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            background: #fff;
            color: #1f9a73;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
        }
        .button:hover, .button:focus-visible { background: #e6f9f3; }
        a:focus-visible { outline: 3px solid #14523d; outline-offset: 3px; }

        /* The sky clips the clouds, so a short screen can still scroll to the button. */
        .sky { position: fixed; inset: 0; overflow: hidden; pointer-events: none; }
        /* Faint, so the white text stays readable while a cloud passes behind it. */
        .cloud {
            position: absolute;
            left: 50%;
            width: 350px;
            height: 120px;
            border-radius: 100px;
            background: #fff;
            opacity: .2;
            animation: drift linear infinite;
        }
        .cloud::before, .cloud::after { content: ""; position: absolute; border-radius: 50%; background: inherit; }
        .cloud::after { top: -50px; left: 50px; width: 100px; height: 100px; }
        .cloud::before { top: -90px; right: 50px; width: 180px; height: 180px; }
        /* The shapes overlap, so the whole cloud is faded as one layer. */
        .cloud { isolation: isolate; }
        .cloud:nth-child(1) { top: 6%; scale: .35; animation-duration: 21s; animation-delay: -4s; }
        .cloud:nth-child(2) { top: 14%; scale: .7; animation-duration: 33s; animation-delay: -19s; }
        .cloud:nth-child(3) { top: 42%; scale: .45; animation-duration: 26s; animation-delay: -12s; }
        .cloud:nth-child(4) { top: 66%; scale: .8; animation-duration: 38s; animation-delay: -2s; }
        .cloud:nth-child(5) { top: 82%; scale: .55; animation-duration: 29s; animation-delay: -23s; }

        /* A short screen, such as a phone held sideways. */
        @media (max-height: 640px) {
            .code { font-size: clamp(5rem, 24vh, 9rem); }
            h1 { margin-top: .75rem; font-size: 1.6rem; }
            p { margin-top: .35rem; font-size: 1rem; }
            .button { margin-top: 1rem; padding-block: .7rem; }
        }

        @keyframes drift {
            from { translate: calc(50vw + 100px) 0; }
            to { translate: calc(-50vw - 450px) 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .cloud { animation: none; }
            .cloud:nth-child(odd) { translate: -38vw 0; }
            .cloud:nth-child(even) { translate: 12vw 0; }
        }
    </style>
</head>
<body>
    <div class="sky" aria-hidden="true">
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
    </div>
    <main>
        <a class="brand" href="/">WEBAPP BẮC NINH</a>
        <div class="code">@yield('code')</div>
        <div class="rule"></div>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <a class="button" href="@yield('action-url', '/')">@yield('action', 'Quay về trang chủ')</a>
    </main>
</body>
</html>
