<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('gorel_favicon.ico') }}" type="image/x-icon">
    <title>Visobra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="login-form mt-4">
        <div class="cotainer mt-4">
            <div class="row justify-content-center">
                <div class="col-md-4 col-lg-4">
                    <div class="card">
                        <img src="https://visobra.regionloreto.gob.pe/v1/lucatiel.png"  class="card-img-top img-fluid" alt="..." >
                        <h3 class="card-header text-center">Login</h3>
                        <div class="card-body">
                            <form id="loginForm" method="POST" action="{{ route ('login.dashboard') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Email" id="email" class="form-control"
                                        name="email" required autofocus>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" placeholder="Password" id="password" class="form-control"
                                        name="password" required>
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
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(function() {
                                window.location.href = '/v1/horizon';
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true 
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errorMessage = 'Error en la solicitud. Por favor, verifica tus datos e inténtalo de nuevo.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                showConfirmButton: true // Mostrar botón de confirmación para errores
                            });
                        } else if (xhr.status === 400) {
                            var errorMessage = 'Error en la solicitud. Por favor, verifica tus datos e inténtalo de nuevo.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                showConfirmButton: true // Mostrar botón de confirmación para errores
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ha ocurrido un error. Por favor, intenta nuevamente más tarde.',
                                showConfirmButton: true // Mostrar botón de confirmación para errores
                            });
                        }
                    }
                });

            });
        });
    </script>
</body>
</html>
