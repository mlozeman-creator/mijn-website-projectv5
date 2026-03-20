<?php
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web-Edu | Vibe Coding & Techniek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; --dark: #0f172a; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        .navbar { background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid #e2e8f0; }
        .hero { 
            background: radial-gradient(circle at top right, #6366f1, #a855f7); 
            color: white; 
            padding: 140px 0 100px; 
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
        }
        .card-blog { 
            border: none; 
            border-radius: 24px; 
            background: white;
            transition: all 0.4s ease;
            border: 1px solid #f1f5f9;
        }
        .card-blog:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
        .badge-vibe { background: linear-gradient(90deg, #6366f1, #a855f7); color: white; border: none; }
        .code-block { background: #1e293b; color: #38bdf8; padding: 25px; border-radius: 16px; font-family: monospace; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="?page=home">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link fw-semibold" href="?page=home">Artikelen</a>
            <a class="nav-link fw-semibold" href="?page=tech">De Techniek</a>
            <a class="nav-link fw-semibold" href="?page=author">De Auteur</a>
        </div>
    </div>
</nav>

<?php if ($page === 'home' && !$articleSlug): ?>
<header class="hero text-center">
    <div class="container">
        <span class="badge badge-vibe px-3 py-2 mb-3 shadow">Powered by Vibe Coding</span>
        <h1 class="display-3 fw-extrabold mb-3">Modern Webonderwijs</h1>
        <p class="lead opacity-90 mx-auto" style="max-width: 600px;">Een kijkje in de keuken van moderne web-ontwikkeling en cloud-technologie.</p>
    </div>
</header>
<?php endif; ?>

<div class="container" style="margin-top: <?php echo ($page === 'home' && !$articleSlug) ? '60px' : '140px'; ?>;">
    
    <?php if ($page === 'home' && !$articleSlug): ?>
        <div class="row g-4">
            <?php foreach ($data['articles'] as $post): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-blog h-100 p-4">
                    <div class="card-body d-flex flex-column">
                        <small class="text-uppercase fw-bold text-primary mb-2"><?php echo $post['category']; ?></small>
                        <h3 class="fw-bold mb-3 text-dark"><?php echo $post['title']; ?></h3>
                        <p class="text-muted mb-4"><?php echo substr($post['content'], 0, 110); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug']; ?>" class="mt-auto btn btn-dark py-3 rounded-pill fw-bold">Lees Artikel</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="fw-extrabold mb-5 display-4 text-dark">De Technische Stack</h1>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="bg-white p-5 rounded-4 shadow-sm h-100 border">
                            <h4 class="fw-bold mb-4 text-primary">Publicatie Flow</h4>
                            <p>CI/CD via GitHub & Vercel. Elke push wordt automatisch live gezet via cloud-automatisering.</p>
                            <div class="code-block mt-4">git commit -m "Nieuwe update" <br> git push origin main</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-white p-5 rounded-4 shadow-sm h-100 border">
                            <h4 class="fw-bold mb-4 text-primary">Backend Engine</h4>
                            <p>Server-side rendering met PHP. Data wordt ingeladen via JSON voor snelheid en flexibiliteit.</p>
                            <div class="code-block mt-4">$data = json_decode($json);</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 bg-white p-5 rounded-5 shadow-lg border">
                    <nav class="mb-5"><a href="?page=home" class="btn btn-light rounded-pill px-4 shadow-sm">← Terug naar overzicht</a></nav>
                    <span class="badge bg-light text-primary mb-3 p-2 px-3"><?php echo $post['category']; ?></span>
                    <h1 class="display-5 fw-extrabold mb-4 text-dark"><?php echo $post['title']; ?></h1>
                    <div class="lh-lg text-secondary fs-5" style="white-space: pre-line;">
                        <?php echo $post['content']; ?>
                    </div>
                    <div class="mt-5 pt-4 border-top">
                        <a href="?page=home" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">Vorige pagina</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-sm border">
                <div class="mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['author']['name']); ?>&size=150&background=6366f1&color=fff" class="rounded-circle shadow-lg border border-5 border-white">
                </div>
                <h2 class="fw-extrabold text-dark"><?php echo $data['author']['name']; ?></h2>
                <p class="text-primary fw-bold">Software Developer & Vibe Coder</p>
                <p class="text-muted fs-5"><?php echo $data['author']['bio']; ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 text-center mt-5">
    <p class="text-muted small fw-bold">© 2026 WEB-EDU PLATFORM - MBO NIVEAU 4</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
