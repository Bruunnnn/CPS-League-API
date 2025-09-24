<link rel="stylesheet" href="{{ asset('/css/login-page.css') }}">

<header>
    @include('partials.header')
</header>

<body>
<div class="login-box">
    <div class="center-box">

        <!-- Login -->
        <div class="login-container" id="login-form">
            <h1 class="title">Login</h1>

            <form class="input-fields" action="/login" method="POST">
                @csrf
                <label class="input-text" for="email">Email</label>
                <input type="email" name="email" placeholder="Email" required>

                <label class="input-text" for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" class="create-button">Login</button>
            </form>

            <p class="switch-text">
                Don't have an account?
                <a href="#" onclick="switchForm('register')">Create one</a>
            </p>
        </div>

        <!-- Register -->
        <div class="login-container" id="register-form" style="display:none;">
            <h1 class="title">Create an account</h1>

            <form class="input-fields" action="/register" method="POST">
                @csrf
                <label class="input-text" for="email">Email</label>
                <input type="email" name="email" placeholder="Email" required>

                <label class="input-text" for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <label class="input-text" for="summoner">Summoner Name</label>
                <input type="text" name="summoner" placeholder="Summoner Name, e.g. MetteF#euw" required>

                <button type="submit" class="create-button">Create Account</button>
            </form>

            <p class="switch-text">
                Already have an account?
                <a href="#" onclick="switchForm('login')">Login</a>
            </p>
        </div>
    </div>
</div>

<script>
    function switchForm(form) {
        document.getElementById("login-form").style.display = form === "login" ? "block" : "none";
        document.getElementById("register-form").style.display = form === "register" ? "block" : "none";
    }
</script>
</body>
