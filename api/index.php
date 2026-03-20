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
    <title>WEB.EDU | Modern Webonderwijs & Techniek</title>
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
            overflow: hidden;
        }
        .card-blog:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.06);
        }
        .card-img-custom {
            height: 180px;
            object-fit: cover;
        }
        .badge-vibe { background: linear-gradient(90deg, #6366f1, #a855f7); color: white; border: none; }
        .code-block { background: #1e293b; color: #38bdf8; padding: 25px; border-radius: 16px; font-family: monospace; }
        .img-article-banner {
            height: 350px;
            object-fit: cover;
            border-radius: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="?page=home">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link fw-semibold" href="?page=home">Feed</a>
            <a class="nav-link fw-semibold" href="?page=tech">Stack</a>
            <a class="nav-link fw-semibold" href="?page=author">Creator</a>
        </div>
    </div>
</nav>

<?php if ($page === 'home' && !$articleSlug): ?>
<header class="hero text-center">
    <div class="container">
        <span class="badge badge-vibe px-3 py-2 mb-3 shadow">Powered by Vibe Coding</span>
        <h1 class="display-3 fw-extrabold mb-3 text-white">Modern Webonderwijs</h1>
        <p class="lead opacity-90 mx-auto text-white" style="max-width: 600px;">Een technische reis door moderne web-ontwikkeling en geautomatiseerde cloud-workflows.</p>
    </div>
</header>
<?php endif; ?>

<div class="container" style="margin-top: <?php echo ($page === 'home' && !$articleSlug) ? '60px' : '140px'; ?>;">
    
    <?php if ($page === 'home' && !$articleSlug): ?>
        <div class="row g-4 mb-5">
            <?php foreach ($data['articles'] as $post): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-blog h-100">
                    <?php if(isset($post['image'])): ?>
                        <img src="<?php echo $post['image']; ?>" class="card-img-top card-img-custom" alt="<?php echo $post['title']; ?>">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column p-4">
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
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <h1 class="fw-extrabold mb-5 display-4 text-dark">Technische Verantwoording</h1>
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="bg-white p-5 rounded-4 shadow-sm h-100 border">
                            <h4 class="fw-bold mb-4 text-primary">CI/CD Publicatie Pijplijn</h4>
                            <p>Volledig geautomatiseerde workflow. Elke **git push** triggert een build op Vercel, waar de PHP-runtime (versie 0.7.2) de server-side uitvoering in een serverless omgeving verzorgt.</p>
                            <div class="code-block mt-4">git commit -m "Vibe upgrade" <br> git push origin main</div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="bg-white p-5 rounded-4 shadow-sm h-100 border">
                            <h4 class="fw-bold mb-4 text-primary">Project Status</h4>
                            <table class="table table-sm table-borderless small">
                                <tr><td>Server Status:</td><td>✅ Online (Vercel)</td></tr>
                                <tr><td>Data Bron:</td><td>📁 JSON Flat-file</td></tr>
                                <tr><td>Frontend:</td><td>🎨 Bootstrap 5.3</td></tr>
                                <tr><td>Logic Engine:</td><td>PHP 8.x</td></tr>
                            </table>
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
            <?php 
                $woorden = str_word_count($post['content']);
                $leestijd = ceil($woorden / 200); // Gemiddeld 200 woorden per minuut
            ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-9">
                    <nav class="mb-4"><a href="?page=home" class="btn btn-light rounded-pill px-4 shadow-sm">← Terug naar overzicht</a></nav>
                    
                    <?php if(isset($post['image'])): ?>
                        <img src="<?php echo $post['image']; ?>" class="img-fluid w-100 img-article-banner mb-5 shadow-lg border-5 border-white" alt="<?php echo $post['title']; ?>">
                    <?php endif; ?>

                    <div class="bg-white p-5 rounded-5 shadow-sm border">
                        <small class="text-uppercase fw-bold text-primary mb-2 d-inline-block"><?php echo $post['category']; ?></small>
                        <h1 class="display-4 fw-extrabold mb-4 text-dark"><?php echo $post['title']; ?></h1>
                        
                        <div class="alert bg-soft-primary text-primary d-flex align-items-center rounded-pill p-2 mb-4">
                            <span class="badge bg-soft-primary px-3 text-dark fw-bold me-2">⏱ <?php echo $leestijd; ?> min leestijd</span>
                            <span>Gebaseerd op 200 woorden per minuut.</span>
                        </div>

                        <div class="lh-lg text-secondary fs-5" style="white-space: pre-line;">
                            <?php echo $post['content']; ?>
                        </div>
                        <div class="mt-5 pt-4 border-top">
                            <a href="?page=home" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">Vorige pagina</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-sm border mb-5">
                <div class="mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['author']['avatar_name']); ?>&size=150&background=6366f1&color=fff" class="rounded-circle shadow-lg border border-5 border-white">
                </div>
                <h2 class="fw-extrabold text-dark"><?php echo $data['author']['name']; ?></h2>
                <p class="text-primary fw-bold">Software Architect & Vibe Coder</p>
                <p class="text-muted fs-5"><?php echo $data['author']['bio']; ?></p>
                
                <hr class="my-4">
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="<?php echo $data['author']['github_url']; ?>" class="btn btn-outline-dark rounded-pill px-4 shadow-sm btn-sm">GitHub</a>
                    <a href="mailto:<?php echo $data['author']['email']; ?>" class="btn btn-dark rounded-pill px-4 shadow-sm btn-sm">Email Mij</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 text-center mt-5 bg-dark text-white">
    <div class="container">
        <p class="fw-bold mb-0">© 2026 WEB-EDU PLATFORM</p>
        <p class="text-muted small">Gemaakt met PHP, Bootstrap, GitHub & Vercel | MBO Software Development (Niveau 4)</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
