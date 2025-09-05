<link rel="stylesheet" href="{{ asset('/css/login-page.css') }}">

<header>
    @include('partials.header')
</header>

<body>
<div class="login-box">
    <div class="center-box">
        <div class="login-container">
            <h1 class="title">Create an account</h1>

            <form class="input-fields" action="/register" method="POST">
                @csrf
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" required>

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <label for="summoner">Summoner Name</label>
                <input name="summoner" placeholder="Summoner Name" required>
        </div>
        <button type="submit" class="create-button">Create Account</button>
        </form>
    </div>
</div>
</body>
