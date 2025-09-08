<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome for Eye Icon -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<style>
body {
    font-family: 'Inter', sans-serif;
    background-color: #d9f2e6; /* Light green background */
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}





/* Login Card */
.card-login {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 15px 25px rgba(0,0,0,0.2);
    padding: 30px;
    width: 100%;
    max-width: 400px;
    margin: 40px auto;
    transition: transform 0.3s;
}
.card-login:hover {
    transform: translateY(-5px);
}
.card-login h3 {
    font-weight: 700;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}
.form-control:focus {
    box-shadow: none;
    border-color: #3aaf8f;
}
.btn-login {
    background: #3aaf8f;
    color: #fff;
    font-weight: 600;
}
.btn-login:hover {
    background: #329977;
}
.password-toggle {
    cursor: pointer;
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #3aaf8f;
}
.remember-me {
    display: flex;
    align-items: center;
}
.remember-me input {
    margin-right: 5px;
}
.forgot-link {
    font-size: 0.9rem;
    color: #3aaf8f;
    text-decoration: none;
}
.forgot-link:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
  <!-- Header -->
  <div id="header"></div>

<h2><center>Admin Loging</center></h2>

<!-- Login Card -->
<div class="card-login">
    <h3>Sign In</h3>
    <form action="login_process.php" method="POST">
        <!-- Username -->
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
            <label class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            <i class="fa-solid fa-eye password-toggle" onclick="togglePassword()"></i>
        </div>

        <!-- Remember Me -->
        <div class="mb-3 remember-me">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-login w-100">Login</button>

        <!-- Forgot Password -->
        <div class="mt-3 text-center">
            <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
        </div>
    </form>
</div>

  <!-- Footer -->
  <div id="footer"></div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
        AOS.init({ duration:1000, once:true, easing:'ease-in-out' });

    // Load header & footer
    fetch("header.html").then(res=>res.text()).then(data=>{document.getElementById("header").innerHTML=data});
    fetch("footer.html").then(res=>res.text()).then(data=>{document.getElementById("footer").innerHTML=data});

</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(){
    const pass = document.getElementById('password');
    const icon = document.querySelector('.password-toggle');
    if(pass.type === 'password'){
        pass.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        pass.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

</body>
</html>
