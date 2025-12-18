<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yummzo | Good Food, Delivered Sustainably</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #FF4757;
            --dark-color: #2F3542;
            --light-bg: #f1f2f6;
            --secondary-color: #2ed573;
        }

        body { font-family: 'Poppins', sans-serif; background-color: var(--light-bg); scroll-behavior: smooth; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }

        /* Existing Styles Preserved */
        .navbar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: 700; font-size: 1.8rem; color: var(--primary-color) !important; letter-spacing: -1px; }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; height: 90vh; display: flex; align-items: center; color: white; text-align: center;
        }
        .hero-title { font-size: 4rem; margin-bottom: 20px; }
        .btn-primary { background-color: var(--primary-color); border: none; padding: 12px 35px; font-weight: 600; border-radius: 50px; transition: 0.3s; }
        .btn-primary:hover { background-color: #e84118; transform: scale(1.05); }

        /* New Visual Additions */
        .feature-card { border: none; border-radius: 25px; padding: 40px; transition: 0.4s; background: #fff; height: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .feature-card:hover { transform: translateY(-15px); box-shadow: 0 20px 40px rgba(255, 71, 87, 0.1); }
        .icon-box { width: 80px; height: 80px; background: rgba(255, 71, 87, 0.1); color: var(--primary-color); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 25px; }
        .gov-tag { background: var(--secondary-color); color: white; padding: 6px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; letter-spacing: 1px; }
        
        /* Stats Section */
        .stat-box { padding: 40px; text-align: center; border-right: 1px solid #ddd; }
        .stat-box:last-child { border-right: none; }
        .stat-number { font-size: 3rem; font-weight: 700; color: var(--dark-color); }

        /* How it Works */
        .step-circle { width: 50px; height: 50px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin-bottom: 20px; font-size: 1.2rem; }
        
        /* Impact Section */
        .impact-section { background: var(--dark-color); color: white; border-radius: 40px; padding: 60px; margin-top: 50px; position: relative; overflow: hidden; }
        .impact-section::after { content: '\f1b9'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; font-size: 15rem; color: rgba(255,255,255,0.05); bottom: -50px; right: -20px; }

        footer { background: #fff; border-top: 1px solid #eee; padding: 60px 0 20px; }
        .footer-link { text-decoration: none; color: #666; transition: 0.3s; }
        .footer-link:hover { color: var(--primary-color); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">YUMMZO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link mx-2 fw-bold" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="auth/register.php?role=restaurant">Register Restaurant</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="auth/register.php?role=delivery">Become a Rider</a></li>
                <li class="nav-item"><a class="btn btn-outline-dark ms-lg-3 rounded-pill px-4" href="auth/login.php">Login</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-2" href="auth/register.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section">
    <div class="container">
        <span class="gov-tag mb-3 d-inline-block">OFFICIAL SUSTAINABILITY PARTNER</span>
        <h1 class="hero-title">Hungry? We’ve Got You Covered.</h1>
        <p class="lead mb-5 fs-4 opacity-75">Order from the best local restaurants and support a zero-waste community.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="auth/login.php" class="btn btn-primary btn-lg px-5 shadow-lg">Explore Food</a>
            <a href="auth/register.php" class="btn btn-light btn-lg rounded-pill px-5">Join the Mission</a>
        </div>
    </div>
</header>

<section class="container mt-n5 position-relative" style="margin-top: -60px; z-index: 10;">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="row g-0">
            <div class="col-md-4 stat-box">
                <div class="stat-number">30+</div>
                <div class="text-muted text-uppercase small fw-bold">Active Kitchens</div>
            </div>
            <div class="col-md-4 stat-box">
                <div class="stat-number text-success">150kg</div>
                <div class="text-muted text-uppercase small fw-bold">Food Waste Prevented</div>
            </div>
            <div class="col-md-4 stat-box">
                <div class="stat-number text-primary">4.9/5</div>
                <div class="text-muted text-uppercase small fw-bold">Safety Score Avg</div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mt-3">The Yummzo Advantage</h2>
            <p class="text-muted">Built for the community, backed by sustainability.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-leaf"></i></div>
                    <h4 class="fw-bold">Zero Food Waste</h4>
                    <p class="text-muted">Our surplus deals help restaurants sell high-quality excess food at huge discounts, reducing carbon footprints.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box" style="background: rgba(46, 213, 115, 0.1); color: #2ed573;"><i class="fas fa-shield-heart"></i></div>
                    <h4 class="fw-bold">Govt. Safety Certified</h4>
                    <p class="text-muted">Access live health inspection scores for every restaurant. We prioritize your health over everything else.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box" style="background: rgba(47, 53, 66, 0.1); color: #2F3542;"><i class="fas fa-bicycle"></i></div>
                    <h4 class="fw-bold">Local Empowerment</h4>
                    <p class="text-muted">A fair-pay platform that puts more money in the hands of delivery partners and local business owners.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <h2 class="display-5 fw-bold mb-4">How Yummzo Works</h2>
                <div class="d-flex mb-4">
                    <div class="step-circle me-3">1</div>
                    <div>
                        <h5 class="fw-bold">Choose your Mood</h5>
                        <p class="text-muted">Browse 30+ menus or check the "Surplus Deals" for amazing food at half the price.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="step-circle me-3">2</div>
                    <div>
                        <h5 class="fw-bold">Restaurant Prepares</h5>
                        <p class="text-muted">Watch your food being prepared with the highest hygiene standards in safety-certified kitchens.</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="step-circle me-3">3</div>
                    <div>
                        <h5 class="fw-bold">Sustainable Delivery</h5>
                        <p class="text-muted">Our riders ensure your food reaches you fast and fresh. Rate your experience to help the community.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 text-center">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/024/678/956/small/delivery-man-transparent-background-png.png" class="img-fluid rounded-5 shadow-lg" alt="Delivery">
            </div>
        </div>
    </div>
</section>

<section class="container mb-5">
    <div class="impact-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="display-4 fw-bold mb-3">Fighting Hunger & Waste</h2>
                <p class="fs-5 opacity-75">Every order from our Surplus Marketplace contributes directly to local NGO programs and reduces city-wide food waste. Join 500+ citizens making a difference.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="auth/register.php" class="btn btn-light btn-lg px-5 fw-bold">Start Now</a>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a class="navbar-brand mb-3 d-block" href="#">YUMMZO</a>
                <p class="text-muted">A modern food delivery initiative focusing on safety, sustainability, and local business growth.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-dark btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-dark btn-sm rounded-circle"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-dark btn-sm rounded-circle"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center text-muted small pb-4">
            &copy; 2025 Yummzo Initiative. All Rights Reserved. Built for Sustainability.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>