<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('gorel_favicon.ico') }}" type="image/x-icon">
    <title>Geobra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="login-form mt-4">
        <div class="cotainer mt-4">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <img src="{{ asset('lucatiel.png') }}" class="img-fluid mx-auto d-block" alt="Remember me"  >
                    <div class="card">
                        <h3 class="card-header text-center">Login</h3>
                        <div class="card-body">
                            <form method="POST" action="{{ route ('login.dashboard') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Email" id="email" class="form-control"
                                        name="email" required autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <input type="password" placeholder="Password" id="password" class="form-control"
                                        name="password" required>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me .- Lucatiel de Mirrah
                                        </label>
                                    </div>
                                </div>
                                <div class="d-grid mx-auto">
                                    <button id="sign" type="button" class="btn btn-dark btn-block">Iniciar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            $('#sign').on('click', function(e) {
                e.preventDefault();
                let formData = $('#loginForm').serialize();
                $.ajax({
                    url: $('#loginForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        let response = response
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response
                            .message, // Suponiendo que el servidor devuelve un objeto JSON con un campo 'message'
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Ejemplo de redirección después de 1.5 segundos
                        setTimeout(function() {
                            window.location.href =
                            '/dashboard'; // Cambia '/dashboard' por la URL a la que deseas redirigir
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing your request. Please try again.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
