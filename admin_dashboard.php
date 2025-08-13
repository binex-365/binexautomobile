<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all cars
    $stmt = $pdo->prepare("SELECT * FROM cars ORDER BY created_at DESC");
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard – Genkada Automobile</title>
    <link rel="icon" href="assets/real-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      :root {
        --highlight: #00ffd0;
        --glow: rgba(55, 228, 255, 0.63);
        --background1: #000d1a;
        --background2: #001d3d;
        --background3: #002b5e;
      }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0e0e2c;
            color: white;
            padding: 20px;
            overflow-y: auto !important;
        }

        .header {
          display: flex;
          justify-content: center;
          align-items: center;
          position: fixed;
          top: 0;
          left: 40%;
          transform: translate-x(-40%);
          height: 80px;
          left: 0;
          right: 0;
          z-index: 2000;
          background: #0e0e2c;
          box-shadow: 0 4px 8px #ffffff4a;
        }

        .header h1 {
            text-shadow: 0 1px 2px black;
          }

          footer {
            background: linear-gradient(90deg, #23283a 0%, #4f8cff 100%);
            color: #fff;
            text-align: center;
            padding: 22px 0 12px 0;
            margin-top: 48px;
            font-size: 1.08rem;
            letter-spacing: 1px;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 -2px 16px #0005;
          }

        @media (max-width: 600px;) {
          .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
          }
        }

        .add-btn {
            border: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .car-grid {
          display: grid;
          grid-template-columns: repeat(3, minmax(280px, 1fr));
          gap: 20px;
          margin-top: 9rem;
        }

        /* Ultrawide monitors (≥1600px) → 4 columns */
        @media screen and (min-width: 1600px) {
            .car-grid {
                grid-template-columns: repeat(4, minmax(280px, 1fr));
            }
        }

        /* Tablets & small laptops (768px–991px) → 2 columns */
        @media screen and (min-width: 768px) and (max-width: 991px) {
            .car-grid {
                grid-template-columns: repeat(2, minmax(250px, 1fr));
            }
        }

        /* Mobile (<768px) → 1 column */
        @media screen and (max-width: 767px) {
            .car-grid {
                grid-template-columns: repeat(1, minmax(250px, 1fr));
            }
        }

        .car-card {
            background: #1a1a40;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            transition: 0.3s;
            padding: 40px;
            white-space: wrap;
            box-sizing: border-box;
        }

        .car-card:hover {
            transform: scale(1.02);
        }

        .car-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .car-card .info {
            padding: 15px;
        }

        .car-card .info h3 {
            margin: 0;
            font-size: 1.1em;
        }

        .modal {
          display: none;
          position: fixed;
          inset: 0;
          background: rgba(0,0,0,0.8);
          z-index: 9999;
          justify-content: center;
          align-items: center;
          max-height: 100vh;
          overflow-y: auto;
          padding: 20px;
          box-sizing: border-box;
          z-index: 9999;
        }

        .modal-content {
            width: 90%;
            max-width: 600px;
            color: white;
            backdrop-filter: blur(50px);
            background: linear-gradient(120deg, #23283a 60%, #4f8cff 80%, #23283a 60%);
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 0 30px var(--glow);
            min-width: 300px;
        }

        .modal-content h2 {
            margin-top: 0;
            text-shadow: 0 1px 2px white;
        }

        .modal-content input {
          width: 100%;
          padding: 15px 10px;
          margin-bottom: 15px;
          background: #26264c;
          border: 1px solid rgba(0, 208, 255, 0.84);
          border-radius: 6px;
          color: white;
          text-shadow: 0px 1px 2px black;
        }

        .modal-content textarea {
          width: 100%;
          padding: 10px;
          margin-bottom: 15px;
          background: #26264c;
          border: 1px solid rgba(0, 208, 255, 0.84);
          border-radius: 6px;
          color: white;
          text-shadow: 0px 1px 2px black;
        }

        .modal-content button {
            padding: 15px 20px;
            background: #1e90ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            text-shadow: 0 1px 2px black;
        }

        .close-btn {
            float: right;
            font-size: 25px;
            cursor: pointer;
            color: #f2f2f2ff;
            text-shadow: 0 1px 2px black;
            transition: all .4s;
        }

        .close-btn:hover {
            color: #dc0000ff;
            text-shadow: 0 1px 2px white;
        }

        .label {
            margin-top: 10px;
            font-weight: bold;
        }

        .car-actions {
          margin-top: 15px;
          display: flex;
          margin-left: 2rem;
        }

        .car-actions form {
          display: inline;
        }

        .delete-btn {
          background: #d02020ff;
          border: none;
          padding: 10px 20px;
          color: white;
          border-radius: 5px;
          cursor: pointer;
          font-size: 16px;
          text-shadow: 0 1px 2px black;
          font-weight: bold;
          transition: all .4s;
        }

        .delete-btn:hover {
          background: #ef2121ff;
          box-shadow: 0px 4px 8px black;
        }
        .custom-modal-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(10, 10, 20, 0.8);
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 9999;
        }

        .custom-modal {
          background: #1a1a2e;
          padding: 30px;
          border-radius: 15px;
          text-align: center;
          color: white;
          box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
          animation: popIn 0.4s ease-out;
        }

        .modal-buttons {
          margin-top: 20px;
        }

        .modal-buttons button {
          padding: 10px 20px;
          margin: 0 10px;
          border: none;
          border-radius: 10px;
          background: #00adb5;
          color: white;
          cursor: pointer;
          font-size: 16px;
          transition: 0.3s;
        }

        .modal-buttons button:hover {
          background: #007c82;
        }

        @keyframes popIn {
          from {
            transform: scale(0.8);
            opacity: 0;
          }
          to {
            transform: scale(1);
            opacity: 1;
          }
        }

        .center {
          position: fixed;
          left: 0;
          z-index: 1000;
          display: flex;
          justify-content: center;
          margin-top: 5.5rem;
          margin-bottom: 40rem;
          background: #0e0e2c;
          right: 0;
          top: -0.6rem;
          padding: 1rem 0;
          padding-top: 1.3rem;
          box-shadow: 0 4px 8px #00000010;
        }

        input::placeholder, textarea::placeholder {
          color: #ffffffa2;
          outline: none;
        }

        input {
          color: #ffffffa2;
          border: none;
          outline: none;
        }

        .add-btn {
          padding: 14px 20px;
          font-size: 15px;
          text-shadow: 0px 1px 2px black;border: none;
          background: linear-gradient(120deg, #23283a 60%, #4f8cff 100%);
          backdrop-filter: blur(30px);
          border: 0.3px solid #4f9bff26;
          box-shadow: 0 4px 8px #5a94ffbc;
          cursor: pointer;
          transition: all 1s;
          color: rgba(222, 222, 222, 1);
        }

        .add-btn:hover {
          background: linear-gradient(120deg, #4f8cff 30%, #23283a 70%);
          color: rgb(255, 255, 255);
          box-shadow: 0 5px 10px #699bf9ce;
        }


    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      text-shadow: 0px 1px 2px black;
    }

    input, textarea {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border-radius: 12px;
      background-color: rgba(255, 255, 255, 0.12);
      color: #fff;
      font-size: 15px;
    }

    input:focus, textarea:focus {
      outline: none;
      background-color: rgba(255, 255, 255, 0.22);
      box-shadow: 0 0 12px var(--highlight);
    }

    .butt {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      font-weight: bold;
      background: linear-gradient(to right, #00ffd0, #007cf0);
      color: #000;
      border: none;
      border-radius: 40px;
      cursor: pointer;
      box-shadow: 0 0 15px var(--glow);
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all .5s;
    }

    input.value {
      text-shadow: 0 1px 2px black;
    }

    .butt:hover {
      background: linear-gradient(to right, #98ffecff, #7abfffff);
      color: black;
      text-shadow: 0 1px 2px black;
    }

    .logout {
      position: absolute;
      right: 20px;
      padding: 13px 30px;
      backdrop-filter: blur(20px);
      background-color: #b30000ff;
      border: none;
      border-top-left-radius: 22px;
      border-bottom-right-radius: 22px;
      color: white;
      text-shadow: 0 2px 4px #bbbbbbb3;
      box-shadow: 0px 4px 8px black;
      cursor: pointer;
      font-size: 15px;
      font-weight: bold;
      transition: all 0.8s;
      text-decoration: none;
    }


    /* Small screens (phones) */
@media screen and (max-width: 768px) {
    .log {
      display: none; /* Hide logo on small screens */
    }
    .logo {
      display: none;
      pointer-events: none; /* Disable pointer events */
    }
}

.logout::before {
  content: 'Log Out';
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0px;
  top: 0px;
  background-color: rgb(172, 0, 0);
  border-top-left-radius: 25px;
  border-bottom-right-radius: 25px;
  display: flex;
  align-items: center;
  z-index: -1;
  justify-content: center;
  transition: all 0.8s;
}

.logout:hover::before {
  transform: scaleX(1.4) scaleY(1.6);
  opacity: 0;
}

.logout:hover {
  transform: translateX(-3px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.logout:active {
  transform: translateY(45px);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

.logo {
    border: 1px solid rgba(3, 75, 143, 0.094);
    height: 70px;
    cursor: pointer;
    box-sizing: border-box;
    transition: all .5s;
    position: absolute;
    left: 20px;
    display: flex;
    align-items: center;
}

.logo:hover {
    box-shadow: 0px 3px 8px rgba(255, 255, 255, 0.204);
}

.log {
    width: 190px;
    margin-top: 0.5rem;
    margin-right: -0.3rem;
}


/* ---------- Success banner styles (paste near end of your <style>) ---------- */
.success-banner {
  position: fixed;
  top: 92px;                /* below your fixed header (header height ~80px) */
  left: 50%;
  transform: translateX(-50%) translateY(-20px);
  z-index: 3001;
  opacity: 0;
  transition: transform 0.45s ease, opacity 0.45s ease;
  pointer-events: none;
  width: auto;
  max-width: 820px;
  padding: 0 12px;
  box-sizing: border-box;
  backdrop-filter: blur(30px);
}

.success-banner.show {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
  pointer-events: auto;
}

.success-inner {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  background: linear-gradient(90deg, rgba(29,185,131,0.12), rgba(0,255,200,0.06));
  border: 1px solid rgba(0,255,200,0.12);
  color: #e8fffb;
  padding: 14px 22px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.45);
  font-weight: 700;
  font-size: 15px;
}

/* small subtitle */
.success-sub {
  font-weight: 600;
  font-size: 13px;
  color: #dffcf3;
  opacity: 0.9;
}


.shake {
    animation: shake 0.4s ease-in-out;
}

@keyframes shake {
    0% { transform: translateX(0); }
    20% { transform: translateX(-5px); }
    40% { transform: translateX(5px); }
    60% { transform: translateX(-5px); }
    80% { transform: translateX(5px); }
    100% { transform: translateX(0); }
}

    </style>
</head>
<body>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
  <div id="successMessage" class="success-banner" aria-live="polite">
    <div class="success-inner">
      <span>✅ Car added successfully!</span>
      <span class="success-sub">Redirecting to car listing page…</span>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const banner = document.getElementById('successMessage');

      // Safety - close add modal if it's open so banner is visible
      const addModal = document.getElementById('addModal');
      if (addModal) addModal.style.display = '

      if (!banner) return;

      // small delay, then show
      setTimeout(() => banner.classList.add('show'), 80);

      // fade out before redirect
      const visibleDuration = 2500; // how long the banner stays visible (ms)
      const fadeBuffer = 450;       // matches CSS transition time
      setTimeout(() => banner.classList.remove('show'), visibleDuration);

      // final redirect after fade completes
      setTimeout(() => {
        // Change this to your desired car listing page if needed
        window.location.href = 'carsales.html';
      }, visibleDuration + fadeBuffer + 120);
    });
  </script>
<?php endif; ?>

<div class="header">
  <a href="index.html" style="text-decoration: none;" class="logo">
    <img src="assets/real-logo.png" class="log">
  </a>

    <h1>Admin Dashboard</h1>
    <button onclick="window.location.href='logout.php'"class='logout'>Log Out</button>
</div>

<div class="center"><button class="add-btn" onclick="document.getElementById('addModal').style.display='flex'">Add New Car</button></div>

<div class="car-grid">
  <?php foreach ($cars as $car): ?>
    <div class="car-card">
        <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>" style="width: 300px; height: auto; border-radius: 10px;">
        <h3><?= htmlspecialchars($car['name']) ?></h3>
        <p><strong>Price:</strong> <?= htmlspecialchars($car['price']) ?></p>
        <p><strong>Model:</strong> <?= htmlspecialchars($car['model']) ?></p>
        <p><strong>Description:</strong> <span class='infoss'><?= nl2br(htmlspecialchars($car['description'])) ?></span></p>

        <!-- Buttons -->
        <div class="car-actions">
          <button onclick="openCarDeleteModal(<?php echo $car['id']; ?>, this.closest('.car-card'))" class="delete-btn">🗑️ Delete</button>
        </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Modal Form -->
<div class="modal" id="addModal">
  <div class="modal-content" style='margin-top: 50rem;'>
    <span class="close-btn" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
    <h2>Add New Car</h2>
    <form action="add_car.php" method="POST" enctype="multipart/form-data">
      <label for="carName">Car Name</label>
      <input type="text" name="name" placeholder="Car Name" required>

      <label for="carPrice">Car Price (e.g ₦3,000,000)</label>
      <input type="text" name="price" value="₦" required>

      <label for="carModel">Car Model</label>
      <input type="text" name="model" id="model" class="form-control" placeholder="Car Model" required>

      <label for="carDesc">Car Description</label>
      <textarea name="description" placeholder="Description" rows="5" style="resize: none;" required></textarea>

      <label class="label">Main Car Image</label>
      <input type="file" name="image" accept="image/*" style="cursor: pointer;" required>

      <label class="label">Front View</label>
      <input type="file" name="front" accept="image/*" style="cursor: pointer;" required>

      <label class="label">Back View</label>
      <input type="file" name="back" accept="image/*" style="cursor: pointer;" required>

      <label class="label">Side View 1</label>
      <input type="file" name="interior" accept="image/*" style="cursor: pointer;" required>

      <label class="label">Side View 2</label>
      <input type="file" name="interior2" accept="image/*" style="cursor: pointer;" required>

      <label class="label">Interior View</label>
      <input type="file" name="exterior" accept="image/*" style="cursor: pointer;" required>

      <label class="label">Engine View</label>
      <input type="file" name="engine" accept="image/*" style="cursor: pointer;" required>

      <button type="submit" class='butt'>Add Car</button>
    </form>
  </div>
</div>

<div id="carDeleteModal" class="custom-modal-overlay" style="display: none;">
  <div class="custom-modal">
    <h2>❗ Are you sure you want to delete this car?</h2>
    <div class="modal-buttons">
      <button id="confirmCarDeleteBtn">Yes</button>
      <button onclick="closeCarDeleteModal()">No</button>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div id="carSuccessModal" class="custom-modal-overlay" style="display: none;">
  <div class="custom-modal">
    <h2>✅ Car deleted successfully!</h2>
  </div>
</div>

<!-- First Modal: Security Check -->
<div class="modal fade" id="lockModal" tabindex="-1" aria-labelledby="lockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Security Check</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="lockError" class="text-danger mb-2" style="display:none;">Incorrect password</div>
        <div class="mb-3">
            <label for="lockPassword" class="form-label">Enter Security Key</label>
            <input type="password" class="form-control" id="lockPassword" placeholder="Enter password">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="checkLockPassword()">Continue</button>
      </div>
    </div>
  </div>
</div>

<!-- Change Admin Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="lockModalLabel">Change Admin Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="changePasswordForm" method="POST" action="change_password.php">
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="new_password" required>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
          </div>
          
          <!-- Error message placeholder -->
          <p id="passwordMismatchError" style="color: red; display: none; margin-bottom: 10px;">Passwords do not match.</p>
          
          <button type="submit" class="btn btn-primary w-100" id="updatePasswordBtn" disabled>Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="container">
    <p>&copy; 2025 Genkada Automobile. All rights reserved<span data-bs-toggle="modal" data-bs-target="#lockModal">.</span></p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Close modal on outside click
  window.onclick = function(e) {
      const modal = document.getElementById('addModal');
      if (e.target === modal) {
          modal.style.display = "none";
      }
  }
</script>

<script>
let carToDeleteElement = null;
let carIdToDelete = null;

// Open modal
function openCarDeleteModal(carId, element) {
  carToDeleteElement = element;
  carIdToDelete = carId;
  document.getElementById('carDeleteModal').style.display = 'flex';
}

// Close modal
function closeCarDeleteModal() {
  document.getElementById('carDeleteModal').style.display = 'none';
  carToDeleteElement = null;
  carIdToDelete = null;
}

// Confirm delete
document.getElementById('confirmCarDeleteBtn').addEventListener('click', function () {
  if (!carToDeleteElement || !carIdToDelete) return;

  fetch('delete_car.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: carIdToDelete })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Remove from DOM
      carToDeleteElement.remove();

      // Remove from localStorage for carsales.html
      const storedCars = JSON.parse(localStorage.getItem('carSales') || '[]');
      const updatedCars = storedCars.filter(car => car.id !== carIdToDelete);
      localStorage.setItem('carSales', JSON.stringify(updatedCars));

      // Show success modal
      document.getElementById('carDeleteModal').style.display = 'none';
      document.getElementById('carSuccessModal').style.display = 'flex';

      // Auto close after 2 seconds
      setTimeout(() => {
        document.getElementById('carSuccessModal').style.display = 'none';
      }, 2000);

      // Auto close after 2 seconds and redirect to carsales.html
      setTimeout(() => {
        window.location.href = 'carsales.html';
      }, 1000);

    } else {
      alert('❌ Failed to delete car.');
    }
  })
  .catch(error => {
    console.error('Error deleting car:', error);
    alert('❌ An error occurred.');
  });
});
</script>

<script>
function checkLockPassword() {
    const entered = document.getElementById('lockPassword').value.trim();
    const errorBox = document.getElementById('lockError');
    if (entered === "6dkj4txz$$$(@)") {
        errorBox.style.display = 'none';
        bootstrap.Modal.getInstance(document.getElementById('lockModal')).hide();
        new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
    } else {
        errorBox.style.display = 'block';
        errorBox.classList.remove("shake");
        void errorBox.offsetWidth; // reset animation
        errorBox.classList.add("shake");
    }
}


const newPasswordInput = document.getElementById('newPassword');
  const confirmPasswordInput = document.getElementById('confirmPassword');
  const updatePasswordBtn = document.getElementById('updatePasswordBtn');
  const passwordMismatchError = document.getElementById('passwordMismatchError');

  // Validation function
  function validatePasswordMatch() {
    const newPass = newPasswordInput.value.trim();
    const confirmPass = confirmPasswordInput.value.trim();

    if (newPass === '' || confirmPass === '') {
      // Disable button and hide error if any field is empty
      updatePasswordBtn.disabled = true;
      passwordMismatchError.style.display = 'none';
      return;
    }

    if (newPass !== confirmPass) {
      updatePasswordBtn.disabled = true;
      passwordMismatchError.style.display = 'block';
    } else {
      updatePasswordBtn.disabled = false;
      passwordMismatchError.style.display = 'none';
    }
  }

  // Attach input event listeners
  newPasswordInput.addEventListener('input', validatePasswordMatch);
  confirmPasswordInput.addEventListener('input', validatePasswordMatch);

  // Optional: run validation on modal show in case fields pre-filled
  $('#changePasswordModal').on('shown.bs.modal', () => {
    validatePasswordMatch();
  });
</script>
</body>
</html>