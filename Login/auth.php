<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FOL Clothing | Auth</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Animate on Scroll -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="Styles/index.css">
  <link rel="stylesheet" href="../Styles/auth.css">
</head>
<body>

  <!-- Header -->
  <div id="header"></div>

  <!-- Auth Container -->
  <section class="auth-container d-flex flex-column flex-md-row justify-content-center align-items-center mt-5 mb-5">
    <!-- Login Card -->
    <div class="auth-card me-md-4 mb-4 mb-md-0 p-4 shadow-lg rounded-4" data-aos="fade-right">
      <h3 class="mb-4 text-center text-success">Login</h3>
      <form id="loginForm">
        <div class="mb-3">
          <input type="email" class="form-control" id="loginEmail" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" id="loginPassword" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Login</button>
      </form>
    </div>

    <!-- Register Card -->
    <div class="auth-card p-4 shadow-lg rounded-4" data-aos="fade-left">
      <h3 class="mb-4 text-center text-success">Register</h3>
      <form id="registerForm">
        <div class="mb-3">
          <input type="text" class="form-control" id="regName" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
          <input type="email" class="form-control" id="regEmail" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input type="text" class="form-control" id="regPhone" placeholder="Contact" required>
        </div>
        <div class="mb-3">
          <input type="text" class="form-control" id="regAddress" placeholder="Address" required>
        </div>
        <div class="mb-3">
          <input type="text" class="form-control" id="regPostal" placeholder="Postal Code" required>
        </div>
        <div class="mb-3">
          <select class="form-control" id="regProvince" required>
            <option value="">Select Province</option>
          </select>
        </div>
        <div class="mb-3">
          <select class="form-control" id="regDistrict" required>
            <option value="">Select District</option>
          </select>
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" id="regPassword" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Register</button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <div id="footer"></div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000 });

    // Load header & footer
    fetch("header.html").then(res=>res.text()).then(data=>{document.getElementById("header").innerHTML=data});
    fetch("footer.html").then(res=>res.text()).then(data=>{document.getElementById("footer").innerHTML=data});

    // Province → District for Sri Lanka
    const sriLanka = {
      "Western": ["Colombo", "Gampaha", "Kalutara"],
      "Central": ["Kandy", "Matale", "Nuwara Eliya"],
      "Southern": ["Galle", "Matara", "Hambantota"],
      "Northern": ["Jaffna", "Kilinochchi", "Mannar", "Vavuniya", "Mullaitivu"],
      "Eastern": ["Trincomalee", "Batticaloa", "Ampara"],
      "North Western": ["Kurunegala", "Puttalam"],
      "North Central": ["Anuradhapura", "Polonnaruwa"],
      "Uva": ["Badulla", "Monaragala"],
      "Sabaragamuwa": ["Ratnapura", "Kegalle"]
    };

    const provinceSelect = document.getElementById('regProvince');
    const districtSelect = document.getElementById('regDistrict');

    for (const prov in sriLanka) {
      const option = document.createElement('option');
      option.value = prov;
      option.textContent = prov;
      provinceSelect.appendChild(option);
    }

    provinceSelect.addEventListener('change', function() {
      const districts = sriLanka[this.value] || [];
      districtSelect.innerHTML = '<option value="">Select District</option>';
      districts.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d;
        opt.textContent = d;
        districtSelect.appendChild(opt);
      });
    });

    // Login
    document.getElementById('loginForm').addEventListener('submit', function(e){
      e.preventDefault();
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;

      fetch('login_user.php', {
        method: 'POST',
        body: JSON.stringify({email,password}),
        headers:{'Content-Type':'application/json'}
      }).then(res=>res.json())
        .then(resp=>{
          if(resp.success){
            Swal.fire('Success', resp.message, 'success').then(()=>{window.location.href='profile.php';});
          } else Swal.fire('Error', resp.message, 'error');
        });
    });

    // Register
    document.getElementById('registerForm').addEventListener('submit', function(e){
      e.preventDefault();
      const name = document.getElementById('regName').value;
      const email = document.getElementById('regEmail').value;
      const phone = document.getElementById('regPhone').value;
      const address = document.getElementById('regAddress').value;
      const postal = document.getElementById('regPostal').value;
      const province = document.getElementById('regProvince').value;
      const district = document.getElementById('regDistrict').value;
      const password = document.getElementById('regPassword').value;

      fetch('send_verification_email.php',{
        method:'POST',
        body:JSON.stringify({name,email,phone,address,postal,province,district,password}),
        headers:{'Content-Type':'application/json'}
      }).then(res=>res.json())
        .then(resp=>{
          if(resp.success){
            Swal.fire('Success','Verification email sent! Please check your inbox.','success');
            document.getElementById('registerForm').reset();
          } else Swal.fire('Error',resp.message,'error');
        });
    });
  </script>
</body>
</html>
