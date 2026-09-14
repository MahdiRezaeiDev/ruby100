<!DOCTYPE html>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title') · Ruby100</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,600,700|syne:700,800" rel="stylesheet" />
    <style>
        :root {
            --ruby: #c8102e;
            --ruby-deep: #8f0b20;
            --ink: #0f1419;
            --steel: #1c2733;
            --mist: #5c6b7a;
            --snow: #f3f6f9;
            --wa: #25d366;
        }
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            min-height: 100%;
            font-family: Outfit, system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 80% 50% at 10% 0%, rgba(200,16,46,.12), transparent 55%),
                radial-gradient(ellipse 60% 40% at 100% 100%, rgba(28,39,51,.08), transparent 50%),
                var(--snow);
        }
        a { color: inherit; text-decoration: none; }
        .wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem 1.25rem;
        }
        .panel {
            width: min(100%, 40rem);
            background: #fff;
            border: 1px solid #d7dee6;
            padding: 2.5rem 2rem;
            box-shadow: 0 24px 60px rgba(15,20,25,.08);
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2rem;
        }
        .mark {
            width: 2.5rem;
            height: 2.5rem;
            display: grid;
            place-items: center;
            background: var(--ruby);
            color: #fff;
            font-family: Syne, sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .brand span {
            font-family: Syne, sans-serif;
            font-weight: 800;
            letter-spacing: -.02em;
            font-size: 1.25rem;
        }
        .code {
            font-family: Syne, sans-serif;
            font-weight: 800;
            font-size: clamp(4.5rem, 16vw, 7rem);
            line-height: .9;
            letter-spacing: -.04em;
            color: var(--ruby);
            margin: 0;
        }
        .title {
            margin: 1rem 0 .75rem;
            font-family: Syne, sans-serif;
            font-weight: 800;
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            letter-spacing: -.02em;
        }
        .message {
            margin: 0 0 2rem;
            color: var(--mist);
            line-height: 1.6;
            font-size: 1.05rem;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .9rem 1.35rem;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            border: 0;
            cursor: pointer;
        }
        .btn-ruby { background: var(--ruby); color: #fff; }
        .btn-ruby:hover { background: var(--ruby-deep); }
        .btn-ink { background: var(--ink); color: #fff; }
        .btn-wa { background: var(--wa); color: #fff; }
        .btn-ghost {
            background: transparent;
            color: var(--steel);
            border: 1px solid #d7dee6;
        }
        .foot {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e8edf2;
            font-size: .85rem;
            color: var(--mist);
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="panel">
            <a class="brand" href="{{ url('/') }}">
                <span class="mark">R</span>
                <span>RUBY100</span>
            </a>

            <p class="code">@yield('code')</p>
            <h1 class="title">@yield('heading')</h1>
            <p class="message">@yield('message')</p>

            <div class="actions">
                @yield('actions')
            </div>

            <div class="foot">
                Towing &amp; car removal · 24/7 · Endeavour Hills, VIC
            </div>
        </div>
    </div>
</body>
</html>
