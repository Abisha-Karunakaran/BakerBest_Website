<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakerBest | Home</title>

   
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
         font-family: 'Poppins', sans-serif;
        }

        body {
            background: url("assets/back.png");
            overflow-x: hidden;
        }

        /* HERO SECTION */
        .hero {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 3% 0 8%;
        }

        .hero-left {
            width: 35%;
        }

        .hero-left h1 {
            font-size: 48px;
            font-weight: 700;
            color: #744542ff;
            margin-bottom: 20px;
        }

        .hero-left p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: black;
        }

     

        .explore-btn {
            margin-top: 30px;
            padding: 12px 30px;
            background: #744542ff;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: .3s;
        }

        .explore-btn:hover {
            background: #744542ff;
        }

        /* HERO IMAGE FADE + ZOOM */
        .hero-right img {
            width: 600px;
            max-width: 100%;
            margin-left: 20px;
            opacity: 0;
            transition: opacity .5s ease-in-out;
            animation: zoomPulse 3.5s ease-in-out 2s infinite;
        }

        /* Zoom animation */
        @keyframes zoomPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
            100% {
                transform: scale(1);
            }
        }

        /* MOBILE HERO FIX */
        @media(max-width:900px) {
            .hero {
                flex-direction: column-reverse;
                text-align: center;
                padding: 40px 5%;
            }
            .hero-left {
                width: 100%;
            }
            .hero-right img {
                width: 80%;
                margin-bottom: 20px;
            }
            .hero-left h1 {
                font-size: 34px;
            }
            .hero-left p {
                font-size: 16px;
            }
        }

        /* ABOUT SECTION */
        .about-section {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 80px 8%;
            background: url("assets/back.png");
            gap: 50px;
        }

        .rotate-container {
            width: 550px;
            height: 550px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.01s linear;
        }

        .rotate-container img {
            width: 100%;
            border-radius: 50%;
        }

        .about-right {
            width: 45%;
        }

        .about-right h2 {
            font-size: 46px;
            font-weight: 700;
            color: #744542ff;
            margin-bottom: 20px;
        }

        .about-right p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #744542ff;
        }

        .menu-btn {
            padding: 14px 32px;
            background: #744542ff;
            border: none;
            font-size: 20px;
            border-radius: 0px;
            color: #fff;
            cursor: pointer;
        }

        .menu-btn:hover {
            background: #744542ff;
        
    }

        /* ABOUT MOBILE FIX */
        @media(max-width:900px) {
            .about-section {
                flex-direction: column;
                padding: 60px 5%;
                text-align: center;
            }
            .rotate-container {
                width: 300px;
                height: 300px;
            }
            .about-right {
                width: 100%;
            }
            .about-right h2 {
                font-size: 32px;
            }
        }

        /* MENU SECTION */
        .menu-section {
            padding: 40px 10%;
            background: #dcd6d5ff;
            text-align: center;
        }

        .menu-title {
            font-size: 42px;
            margin-bottom: 30px;
            color: #744542ff;
            margin-top: 20px;
        }

        .menu-grid {
           
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 35px;
    margin-top: 60px;
    margin-bottom: 70px;
    flex-wrap: nowrap; /* DO NOT wrap */
    overflow-x: auto;  /* Allows scroll on small screens */
    padding-bottom: 15px;
}

            
        
        .menu-item {
            background: #ffffff;
            padding: 20px;
            border-radius: 18px;
            border: 2px solid #807b7bff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: .3s;
        }

        .menu-item:hover {
            transform: translateY(-5px);
        }

        .menu-item img {
    width: 170px;      /* Fixed width */
    height: 200px;     /* Fixed height */
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 15px;
}


        .menu-item h3 {
            font-size: 20px;
            color: #744542ff;
            margin-bottom: 8px;
        }

        .menu-item p {
            font-size: 15px;
            color: #744542ff;
        }
        .btn {
            padding: 14px 32px;
            background: #744542ff;
            border: none;
            font-size: 20px;
            border-radius: 0px;
            color: #fff;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-left">
            <h1>Where every bake feels like home.</h1>
            <p>Whether you crave something sweet, fluffy, or buttery, our bakery offers a delicious treat for every mood.</p>

            

            <button class="explore-btn" onclick="location.href='about.php'">Explore more</button>
        </div>

        <div class="hero-right">
            <img id="homeImage" src="" alt="Bake goods">
        </div>
    </section>




    <!-- MENU SECTION -->
    <section class="menu-section" id="menu">
        <h2 class="menu-title">Our Menu Categories</h2>

        <div class="menu-grid">

            <div class="menu-item">
                <img src="assets/bread.jpeg" alt="Breads">
                <h3>Breads</h3>
            </div>

            <div class="menu-item">
                <img src="assets/buns.jpg" alt="Buns">
                <h3>Buns</h3>
            </div>

            <div class="menu-item">
                <img src="assets/cake.jpeg" alt="Cakes">
                <h3>Cakes</h3>
            </div>

            <div class="menu-item">
                <img src="assets/a4.jpeg" alt="Biscuits">
                <h3>Cookies & Biscuits</h3>
            </div>

            <div>
                <div class="menu-item">
                    <img src="assets/a5.jpeg" alt="Pastries">
                    <h3>Pastries</h3>
                </div>
            </div>

          
        </div>
          <button class="btn" onclick="location.href='menu.php'">View Full Menu</button>
     
    </section>




    <!-- ABOUT SECTION -->
    <section class="about-section">
        <div class="about-left">
            <div class="rotate-container" id="rotateBox">
                <img src="assets/cook.png" alt="Bakery Dessert">
            </div>
        </div>

        <div class="about-right">
            <h2>Fresh flavors baked with love</h2>
            <p>
                At BakerBest, every creation is crafted with passion and the finest ingredients.
                From warm buttery croissants and artisan breads to delightful cakes and treats -
                we bake happiness into every bite.
            </p>

            <button class="menu-btn" onclick="location.href='about.php'">More About Us</button>
        </div>
    </section>




    <script>
        /* HERO IMAGE CHANGE */
        document.addEventListener("DOMContentLoaded", function() {
            const images = [
                "assets/1.png",
                "assets/2.png",
                "assets/3.png",
                "assets/5.png",
                "assets/6.png"
            ];

            let currentIndex = 0;
            const homeImage = document.getElementById("homeImage");

            if (homeImage) {
                homeImage.src = images[0];
                homeImage.style.opacity = 1;

                setInterval(() => {
                    homeImage.style.opacity = 0;

                    setTimeout(() => {
                        currentIndex = (currentIndex + 1) % images.length;
                        homeImage.src = images[currentIndex];
                        homeImage.style.opacity = 1;
                    }, 300);

                }, 5000);
            }
        });

        /* SCROLL-BASED ROTATION */
        const rotateBox = document.getElementById("rotateBox");

        window.addEventListener("scroll", function() {
            const rect = rotateBox.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            if (rect.top < windowHeight && rect.bottom > 0) {
                let rotateValue = window.scrollY / 2;
                rotateBox.style.transform = "rotate(" + rotateValue + "deg)";
            }
        });
    </script>

</body>

</html>

<?php include 'footer.php'; ?>
