<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #00c6ff, #0072ff); color: #333; font-family: Arial, sans-serif; }
        .navbar { background: #343a40; }
        .navbar-brand, .navbar-nav .nav-link, .logout-link { color: #fff; font-size: 1.1rem; }
        .navbar-nav .nav-link:hover, .logout-link:hover { color: #e0e0e0; text-decoration: underline; }
        .container { background: #f8f9fa; border-radius: 0.5rem; box-shadow: 0 0 1rem rgba(0,0,0,0.1); padding: 2rem; margin-top: 2rem; }
        h1 { color: #0072ff; }
        .btn-primary { background: #28a745; border: none; }
        .btn-primary:hover { background: #218838; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">TechNepal</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#profile">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings">Settings</a></li>
            </ul>
            <a class="logout-link" href="loginPage.php">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>Welcome to Your Dashboard!</h1>
        <p>This is the home page where you can find the latest updates and information.</p>
        <button type="button" class="btn btn-primary">Get Started</button>
    </div>
</body>
</html>
