<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login with Google One Tap</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <h1 id="status">Not Signed In</h1>

    <script>
        function handleCredentialResponse(response) {
            console.log("ID token:", response.credential);

            fetch('/google-signin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    credential: response.credential
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('status').innerText = 'Signed in as ' + data.name;
                    window.location.href = data.redirect_url || '/dashboard';
                } else {
                    document.getElementById('status').innerText = 'Login failed: ' + data.error;
                    console.error(data.error);
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                document.getElementById('status').innerText = 'Login error, see console.';
            });
        }

        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "801852436975-qlp2cphgt30t33bokdsr5ratli1mhhqt.apps.googleusercontent.com",
                callback: handleCredentialResponse,
                auto_select: false
            });
            google.accounts.id.prompt();
        };
    </script>
</body>
</html>
