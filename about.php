<?php include 'header.php'; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Artian Bakery</title>

<style>
/* MAIN PAGE WRAPPER */
body {
    background:  #dcd6d5ff;
    font-family: 'Poppins', sans-serif;
    color: #744542ff;
}

/* ==============================
   1. ABOUT STORY SECTION
============================== */
.about-section {
    
    background: url("assets/back.png");
    width: 100%;
    padding: 120px 10% 60px;
    display: flex;
    align-items: center;
    gap: 40px;
    flex-wrap: wrap;
}

.about-section img {
    width: 420px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.about-content {
    flex: 1;
    min-width: 300px;
}

.about-content h2 {
    font-size: 42px;
    margin-bottom: 20px;
    color: #744542ff;
    font-family: 'Great Vibes', cursive;
}

.about-content p {
    font-size: 20px;
    line-height: 2;
    margin-bottom: 15px;
}

/* ==============================
   2. CORE VALUES
============================== */
.values-section {
    font-family: 'Poppins', sans-serif;
    padding: 30px 10%;
    text-align: center;
    margin-top: 100px;
    margin-bottom: 120px;
}

.values-section h2 {
    font-size: 38px;
    margin-bottom: 70px;
    color: #744542ff;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 60px;
   
}

.value-box {
    background: white;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.value-box:hover {
    transform: translateY(-6px);
}

.value-box h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #744542ff;
}

.value-box p {
    font-size: 15px;
    line-height: 1.5;
}

/* ==============================
   3. WHO WE SERVE
============================== */
.serve-section {
    padding: 50px 10%;
    background: url("assets/back.png");
    margin-top: 20px;
    margin-bottom: 40px;
}

.serve-section h2 {
    font-size: 40px;
    text-align: center;
    margin-bottom: 40px;
}

.serve-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
}

.serve-box {
    background: white;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    transition: .3s;
}

.serve-box:hover {
    transform: translateY(-6px);
}

.serve-box h3 {
    font-size: 22px;
    color: #744542ff;
    margin-bottom: 12px;
    
}


/* ==============================
   TEAM SECTION
============================== */
.team-section {
    padding: 60px 10%;
}

.team-section h2 {
    text-align: center;
    font-size: 40px;
    margin-bottom: 40px;
    color: #744542ff;
}

/* ONE ROW GRID */
.team-grid {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: 260px; /* width of each card */
    gap: 28px;
    overflow-x: auto; /* scroll if needed */
    padding-bottom: 10px;
    white-space: nowrap;
    height: 350px;
}

/* TEAM CARD */
.team-card {
    background: white;
    border-radius: 18px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
    transition: 0.3s;
    width: 250px;
}

.team-card:hover {
    transform: translateY(-6px);
}

/* IMAGE */
.team-img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 15px;
    object-fit: cover;
    box-shadow: 0 0 12px rgba(0,0,0,0.15);
}

/* NAME */
.team-card h3 {
    font-size: 20px;
    margin-bottom: 6px;
    color: #744542ff;
}

/* ROLE / DESCRIPTION */
.team-card p {
    font-size: 14px; /* FIXED & BALANCED SIZE */
    color: #744542ff;
}


/* RESPONSIVE */
@media(max-width: 900px) {
    .about-section {
        text-align: center;
        display: block;
    }
    .about-section img {
        width: 100%;
        max-width: 350px;
        margin: auto;
    }
}
</style>
</head>

<body>

<!-- ==========================
     1. ABOUT STORY SECTION
========================== -->
<section class="about-section">
    <img src="assets/a1.jpg" alt="Bakery Chef">
    <div class="about-content">
        <h2>Our Story</h2>
        <p>Born in the heart of Jaffna, Artian Bakery began with a simple dream - to create a space filled with fresh bakes, warm aromas, and heartfelt flavours.</p>
        <p>Every recipe is handcrafted with care, blending tradition with modern creativity to bring joy to every customer.</p>
    </div>
</section>

<!-- ==========================
     2. CORE VALUES
========================== -->
<section class="values-section">
    <h2>Our Core Values</h2>

    <div class="values-grid">
        <div class="value-box">
            <h3>❤️ Quality First</h3>
            <p>We use premium ingredients with no shortcuts.</p>
        </div>

        <div class="value-box">
            <h3>🎨 Creativity</h3>
            <p>Every bake is crafted with artistry and precision.</p>
        </div>

        <div class="value-box">
            <h3>🤝 Community Love</h3>
            <p>Inspired by Jaffna’s warm and vibrant community.</p>
        </div>

        <div class="value-box">
            <h3>🍞 Freshness Always</h3>
            <p>Everything is baked fresh daily with passion.</p>
        </div>
    </div>
</section>

<!-- ==========================
     3. WHO WE SERVE
========================== -->
<section class="serve-section">
    <h2>⭐ Who We Serve</h2>

    <div class="serve-grid">

        <div class="serve-box">
            <h3>Local Families & Everyday Customers</h3>
            <p>From breakfast breads to evening treats, we serve families across Jaffna who love fresh, comforting bakes.</p>
        </div>

        <div class="serve-box">
            <h3>Working Professionals</h3>
            <p>Quick breakfast options, office snacks, and reliable online ordering for meetings & celebrations.</p>
        </div>

        <div class="serve-box">
            <h3>Foodies & Dessert Lovers</h3>
            <p>Elegant pastries, custom cakes, and beautiful creations designed to impress the eye and taste buds.</p>
        </div>

        <div class="serve-box">
            <h3>Tourists Exploring Jaffna</h3>
            <p>A mix of local flavours and modern bakery trends that showcase the culture of Jaffna.</p>
        </div>

        <div class="serve-box">
            <h3>Online Shoppers</h3>
            <p>Order conveniently from home - fresh bakery items delivered right to your doorstep.</p>
        </div>
    </div>

</section>

<!-- ==========================
     4. OUR SPECIALISTS SECTION
========================== -->
<section class="team-section">
    <h2>Our Baking Specialists</h2>

    <div class="team-grid">

        <div class="team-card">
            <img class="team-img" src="assets/b1.jpeg">
            <h3>Bread Specialist</h3>
            <p>Master of artisan breads, <BR>
        crispy crusts & soft textures.</p>
        </div>

        <div class="team-card">
            <img class="team-img" src="assets/b2.jpeg">
            <h3>Cake Specialist</h3>
            <p>Designer of elegant celebration <br>cakes & custom creations.</p>
        </div>

        <div class="team-card">
            <img class="team-img" src="assets/b3.jpeg">
            <h3>Biscuits & Cookies</h3>
            <p>Crunchy, sweet, buttery <br>- crafted with perfection.</p>
        </div>

        <div class="team-card">
            <img class="team-img" src="assets/b4.jpeg">
            <h3>Pastries Specialist</h3>
            <p>Handcrafted pastries with <br>layers, flavours & artistry.</p>
        </div>

        <div class="team-card">
            <img class="team-img" src="assets/b5.jpeg">
            <h3>Main Head Baker</h3>
            <p>Leading the team with <br>passion, skill & creativity.</p>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
