<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    @if(session()->has('user'))
        <h2>Welcome, {{ session('user.name') }}</h2>
        <p>Email: {{ session('user.email') }}</p>
        <a href="{{ route('logout') }}">Logout</a>
    @else
        <div id="g_id_onload"
             data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
             data-context="signin"
             data-callback="onGoogleSignIn"
             data-auto_prompt="true">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-size="large"
             data-theme="outline"
             data-text="signin_with"
             data-shape="rectangular"
             data-logo_alignment="left">
        </div>
    @endif

    <script>
        function onGoogleSignIn(response) {
            const formData = new FormData();
            formData.append("id_token", response.credential);

            fetch("{{ route('google.signin') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === "Login successful") {
                    location.reload();
                } else {
                    alert(data.error || "Login failed");
                }
            });
        }
    </script>
</body>
</html>
