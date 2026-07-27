<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/login_custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   
</head>

<body>

<div class="login-container">

    <!-- LEFT SIDE -->
    <div class="left-section">
        <div class="slider-text">
            <div class="star-icon">
                <img src="{{ asset('assets/images/star_icon.png') }}" alt="star">
            </div>
            <div class="slide active">
                <h2>Your Workspace Awaits</h2>
                <p>Access your portal and start managing your tasks with ease and efficiency.</p>
            </div>

            <div class="slide">
                <h2>Manage Your Work</h2>
                <p>Track progress and improve productivity with powerful tools.</p>
            </div>

            <div class="slide">
                <h2>Stay Organized</h2>
                <p>Everything you need in one place.</p>
            </div>
            <!-- Slider Arrows -->
            

        </div>
        <button class="slider-arrow left-arrow">&#10094;</button>
        <button class="slider-arrow right-arrow">&#10095;</button>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right-section">

        <div class="login-box">

            <div class="logo">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo">
            </div>

            <h3 class="text-left">Welcome to Exacore Portal</h3>
            <p class="text-left">Manage your business operations with ease.</p>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

                <form method="POST" action="{{ route('login.post') }}" class="mt-3">
                @csrf

                <div class="mb-3">
                    <label>Email ID</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>

                <div class="mb-3 password-wrapper">
                    <label>Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end mb-3">
                    {{-- <a href="#">Forgot password?</a> --}}
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Log In
                </button>

            </form>

            

        </div>
        <p class="bottom-text">
            Your information is securely protected.
        </p>

    </div>

</div>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const icon = document.querySelector(".toggle-password i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
    let slides = document.querySelectorAll(".slide");
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove("active"));
        slides[index].classList.add("active");
    }

    // Auto slider
    setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 3000);

    // Right arrow
    document.querySelector(".right-arrow").addEventListener("click", () => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    });

    // Left arrow
    document.querySelector(".left-arrow").addEventListener("click", () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    });

    
</script>

</body>
</html>