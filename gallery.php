<?php include 'header.php'; 
include 'Backend/db.php';

$images = mysqli_query($conn, "SELECT * FROM gallery_images ORDER BY created_at DESC");
?>

<style>
.gallery-section { padding: 120px 10%; background: url('assets/back.png'); }
.gallery-section h2 { text-align:center; font-size:42px; margin-bottom:15px; color:#59331D; font-weight:700; }
.gallery-section p { text-align:center; font-size:17px; color:#6b4630; margin-bottom:40px; }

.gallery-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:22px; }
.gallery-item { position:relative; overflow:hidden; border-radius:12px; cursor:pointer; aspect-ratio:4/3; }
.gallery-item img { width:100%; height:100%; object-fit:cover; display:block; border-radius:12px; transition: transform 0.4s ease; }
.gallery-item:hover img { transform:scale(1.08); }

.lightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); justify-content:center; align-items:center; z-index:9999; }
.lightbox img { width:70%; max-width:850px; border-radius:15px; animation:fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from{opacity:0;} to{opacity:1;} }
.lightbox:target { display:flex; }
.close-btn { position:absolute; top:40px; right:50px; font-size:45px; color:#fff; text-decoration:none; }

@media(max-width:600px){ .gallery-item { aspect-ratio:1/1; } }
</style>

<section class="gallery-section">
    <h2>Our Gallery</h2>
    <p>Freshly baked goodness captured with love — breads, cakes, pastries & more.</p>

    <div class="gallery-grid">
        <?php 
        $i = 1;
        while($row = mysqli_fetch_assoc($images)): ?>
            <a href="#img<?= $i ?>" class="gallery-item">
                <img src="Backend/<?= $row['image_path'] ?>" alt="<?= htmlspecialchars($row['title']) ?>">
            </a>
            <div class="lightbox" id="img<?= $i ?>">
                <a href="#" class="close-btn">&times;</a>
                <img src="Backend/<?= $row['image_path'] ?>" alt="<?= htmlspecialchars($row['title']) ?>">
            </div>
        <?php $i++; endwhile; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
