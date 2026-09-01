<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Proof of Content')</title>
    @include('layouts.partials.head')
</head>
<body>
    <div class="wrapper">
        @include('layouts.partials.sidebar')
        
        <main class="main-content">
            @include('layouts.partials.topbar')
            
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </div>

    @include('layouts.partials.scripts')
</body>
</html>
