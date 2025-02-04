<style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin-left: 25wh;
        padding: 0;
    }

    .form-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
        width: 400px;
        max-width: 100%;
        text-align: center;
        z-index: 3;
    }

    .form-container h2 {
        margin-bottom: 20px;
        color: var(--color-primary);
    }

    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"],
    .form-container button {
        width: 100%;
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-container button {
        background-color: var(--color-accent);
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container button:hover {
        background-color: var(--color-highlight);
    }

    .form-switch {
        margin-top: 20px;
    }

    .form-switch a {
        color: var(--color-primary);
        text-decoration: none;
    }

    .form-switch a:hover {
        text-decoration: underline;
    }

    /* Hide sign-up form by default */
    .sign-up-form {
        display: none;
    }

    /* Show sign-up form when .show-sign-up class is applied */
    .show-sign-up .sign-in-form {
        display: none;
    }

    .show-sign-up .sign-up-form {
        display: block;
    }

    .circles {
        position: absolute;
        top: 20vh;
        left: 0;
        width: 100%;
        height: 88vh;
        overflow: hidden;
    }

    .circles li {
        position: absolute;
        display: block;
        list-style: none;
        width: 20px;
        height: 20px;
        background: var(--color-accent);
        animation: animate 25s linear infinite;
        bottom: -150px;

    }

    .circles li:hover {
        background: var(--color-highlight);
    }

    .circles li:nth-child(1) {
        left: 25%;
        width: 80px;
        height: 80px;
        animation-delay: 0s;
    }


    .circles li:nth-child(2) {
        left: 10%;
        width: 20px;
        height: 20px;
        animation-delay: 2s;
        animation-duration: 12s;
    }

    .circles li:nth-child(3) {
        left: 70%;
        width: 20px;
        height: 20px;
        animation-delay: 4s;
    }

    .circles li:nth-child(4) {
        left: 40%;
        width: 60px;
        height: 60px;
        animation-delay: 0s;
        animation-duration: 18s;
    }

    .circles li:nth-child(5) {
        left: 65%;
        width: 20px;
        height: 20px;
        animation-delay: 0s;
    }

    .circles li:nth-child(6) {
        left: 75%;
        width: 110px;
        height: 110px;
        animation-delay: 3s;
    }

    .circles li:nth-child(7) {
        left: 35%;
        width: 150px;
        height: 150px;
        animation-delay: 7s;
    }

    .circles li:nth-child(8) {
        left: 50%;
        width: 25px;
        height: 25px;
        animation-delay: 15s;
        animation-duration: 45s;
    }

    .circles li:nth-child(9) {
        left: 20%;
        width: 15px;
        height: 15px;
        animation-delay: 2s;
        animation-duration: 35s;
    }

    .circles li:nth-child(10) {
        left: 85%;
        width: 150px;
        height: 150px;
        animation-delay: 0s;
        animation-duration: 11s;
    }



    @keyframes animate {

        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
            border-radius: 0;
        }

        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
            border-radius: 50%;
        }

    }
</style>

<div class="container">
    <div class="form-container">
        <form class="sign-in-form" action="functions/loginHandler.php" method="POST">
            <h2>Sign In</h2>
            <div class="mb-3">
                <input type="text" class="form-control" id="emailOrUsername" name="emailOrUsername" placeholder="Enter Email or Username"
                    required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
        <form class="sign-up-form" action="functions/registrationHandler.php" method="POST">
    <h2 class="mb-4">Sign Up</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" name="firstname" placeholder="Firstname" required>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" name="lastname" placeholder="Lastname" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <select class="form-control" name="gender" required>
                <option value="" disabled selected>Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="trans">Trans</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="number" class="form-control" name="age" placeholder="Age" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" name="homecity" placeholder="Home City" required>
        </div>
        <div class="col-md-6">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
        </div>
        <div class="col-md-6">
            <select class="form-control" name="usertype" required>
                <option value="" disabled selected>User Type</option>
                <option value="normal">Normal User</option>
                <option value="advertiser">Advertiser</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <div class="col-md-6">
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Sign Up</button>
</form>


        <div class="form-switch">
            <a href="#" id="switchToSignUp">Don't have an account? Sign Up</a><br>
            <a href="#" id="switchToSignIn" class="show-sign-up">Already have an account? Sign In</a>
        </div>
    </div>
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

<script>
    const switchToSignUp = document.getElementById('switchToSignUp');
    const switchToSignIn = document.getElementById('switchToSignIn');
    const formContainer = document.querySelector('.form-container');
    switchToSignUp.addEventListener('click', function(e) {
        e.preventDefault();
        formContainer.classList.add('show-sign-up');
    });
    switchToSignIn.addEventListener('click', function(e) {
        e.preventDefault();
        formContainer.classList.remove('show-sign-up');
    });
</script>