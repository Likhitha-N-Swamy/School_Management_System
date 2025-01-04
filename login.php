<?php include('header.php') ?>
<style>
   body {
        margin: 0;
        padding: 0;
        background-image: url('background.jpg'); /* Add your background image here */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh; /* Full viewport height */
        display: flex;
        justify-content: center;
        align-items: center; /* Center vertically */
        font-family: Arial, sans-serif;
    }
    .btn {
        background-color: #4CAF50;
        color: white;
    }
    .card {
        width: 200%;
        max-width: 500px; /* Set a maximum width for the card */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
    .card-body {
        padding: 20px;
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="border rounded-circle mx-auto d-flex" style="width:100px; height:100px; background-color: #6c757d;">
            <i class="fa fa-user text-light fa-3x m-auto"></i>
        </div>
        <form action="actions/login.php" method="POST" onsubmit="return showPopup()">
            <!-- Material input -->
            <div class="md-form">
                <input type="text" id="email" name="email" class="form-control" required>
                <label for="email">Your Email</label>
            </div>
            <!-- Material input -->
            <div class="md-form">
                <input type="password" id="password" name="password" class="form-control" required>
                <label for="password">Your Password</label>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary" name="login">Login</button>
            </div>
        </form>
    </div>
</div>


<?php include('footer.php') ?>
