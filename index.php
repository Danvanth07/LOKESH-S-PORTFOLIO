<?php
/**
 * LOKESH PON - Civil Engineering Portfolio
 * Backend: PHP 8.2 | Frontend: Vue.js 3
 */

$dataFile = __DIR__ . '/data/portfolio_data.json';
$portfolioData = [];

if (file_exists($dataFile)) {
    $raw = file_get_contents($dataFile);
    $portfolioData = json_decode($raw, true) ?: [];
}

$pageTitle = ($portfolioData['profile']['fullName'] ?? 'Lokesh Pon') . " | Civil Engineering Portfolio";
$initialDataJson = json_encode($portfolioData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="theme-color" content="#0a0f1d">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  
  <meta name="description" content="Civil Engineering Portfolio of Lokesh Pon - Government College of Engineering (IRTT), Erode. Specializing in BIM, Architectural Drafting, 3D Visualization (Revit, SketchUp, Enscape, AutoCAD), and Structural Analysis (STAAD.Pro).">
  <meta name="keywords" content="Lokesh Pon, Civil Engineering Portfolio, IRTT Erode, Revit BIM, SketchUp Enscape, AutoCAD Civil Drafting, STAAD Pro, Pile Foundation">
  <meta name="author" content="Lokesh Pon">

  <!-- Open Graph -->
  <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
  <meta property="og:description" content="B.E. Civil Engineering Student at Government College of Engineering (IRTT), Erode. Explore 2D/3D CAD projects, BIM models, and site experience.">
  <meta property="og:image" content="assets/images/sketchup-villa-night.jpg">
  <meta property="og:type" content="website">

  <!-- Favicon & PWA Manifest -->
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
  <link rel="apple-touch-icon" href="assets/images/favicon.svg">
  <link rel="manifest" href="manifest.json">

  <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome CDN with fallback -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Custom Architectural & Civil Engineering Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">

  <script>
    // Server-hydrated initial data
    window.INITIAL_PORTFOLIO_DATA = <?php echo $initialDataJson ?: '{}'; ?>;
  </script>
</head>
<body>
<div id="app" v-cloak>

  <!-- ==========================================
       NAVIGATION BAR
       ========================================== -->
  <nav class="navbar">
    <div class="container nav-container">
      <div class="nav-brand">
        <div class="nav-logo-icon">
          <i class="fa-solid fa-compass-drafting"></i>
        </div>
        <div class="nav-brand-text">
          <h1>{{ portfolio?.profile?.fullName || 'LOKESH PON' }}</h1>
          <span>B.E. Civil Engineering</span>
        </div>
      </div>

      <ul class="nav-links" :class="{ 'mobile-open': mobileMenuOpen }">
        <li class="nav-item"><a href="#home" @click="scrollToSection('home')">Home</a></li>
        <li class="nav-item"><a href="#about" @click="scrollToSection('about')">About</a></li>
        <li class="nav-item"><a href="#education" @click="scrollToSection('education')">Education</a></li>
        <li class="nav-item"><a href="#skills" @click="scrollToSection('skills')">Skills</a></li>
        <li class="nav-item"><a href="#projects" @click="scrollToSection('projects')">Projects</a></li>
        <li class="nav-item"><a href="#internships" @click="scrollToSection('internships')">Internships</a></li>
        <li class="nav-item"><a href="#certifications" @click="scrollToSection('certifications')">Certs</a></li>
        <li class="nav-item"><a href="#field-work" @click="scrollToSection('field-work')">Field Work</a></li>
        <li class="nav-item"><a href="#achievements" @click="scrollToSection('achievements')">Activities</a></li>
        <li class="nav-item"><a href="#contact" @click="scrollToSection('contact')">Contact</a></li>

        <!-- Mobile Drawer Quick Actions (Visible only in mobile drawer) -->
        <li class="mobile-drawer-cta">
          <button class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 10px;" @click="downloadResume('resume'); mobileMenuOpen = false;">
            <i class="fa-solid fa-file-arrow-down"></i>
            <span>Download Resume (PDF)</span>
          </button>
          <button class="btn btn-outline" style="width: 100%; justify-content: center;" @click="openUploadModal('coverPhoto'); mobileMenuOpen = false;">
            <i class="fa-solid fa-camera"></i>
            <span>Change Cover Photo</span>
          </button>
        </li>
      </ul>

      <div class="nav-actions">
        <!-- Theme Toggle -->
        <button class="icon-btn" @click="toggleTheme" :title="currentTheme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
          <i :class="currentTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon'"></i>
        </button>

        <!-- Quick Upload Cover Button (Desktop only; on mobile accessible via drawer & hero trigger) -->
        <button class="btn btn-outline btn-cover-nav" style="padding: 7px 14px; font-size: 0.8rem;" @click="openUploadModal('coverPhoto')" title="Upload or Change Cover Photo">
          <i class="fa-solid fa-image"></i>
          <span>Cover Photo</span>
        </button>

        <!-- Resume Download CTA -->
        <button class="btn btn-primary btn-resume-nav" style="padding: 7px 16px; font-size: 0.82rem;" @click="downloadResume('resume')">
          <i class="fa-solid fa-file-arrow-down"></i>
          <span>Resume</span>
        </button>

        <!-- Mobile Menu Toggle -->
        <button class="icon-btn mobile-toggle" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle navigation menu">
          <i :class="mobileMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
        </button>
      </div>
    </div>
  </nav>

  <!-- ==========================================
       1. HOME / HERO SECTION (MANDATORY)
       ========================================== -->
  <section id="home" class="hero-section">
    <div class="container">
      
      <!-- Cover Photo with Upload Button -->
      <div class="hero-cover-wrapper">
        <img :src="portfolio?.profile?.coverPhoto || 'assets/images/hero-cover.jpg'" alt="Architectural 3D Cover" class="hero-cover-img">
        <div class="hero-cover-overlay"></div>
        
        <div class="cover-badge-tag">
          <i class="fa-solid fa-cube"></i>
          <span>3D Architectural Rendering (SketchUp + Enscape)</span>
        </div>

        <button class="cover-upload-trigger" @click="openUploadModal('coverPhoto')">
          <i class="fa-solid fa-camera"></i>
          <span>Change Cover Photo</span>
        </button>
      </div>

      <!-- Hero Main Profile Card -->
      <div class="hero-content-card glass-panel">
        <div class="hero-main-flex">
          
          <!-- Professional Photograph / Avatar -->
          <div class="hero-avatar-col">
            <div class="hero-avatar-box">
              <img :src="portfolio?.profile?.avatar || 'assets/images/lokesh-photo.jpg'" alt="Lokesh Pon" class="hero-avatar-img" @error="$event.target.src='assets/images/lokesh-photo.jpg'">
              <button class="avatar-edit-btn" @click="openUploadModal('avatar')" title="Update Profile Photo">
                <i class="fa-solid fa-pen"></i>
              </button>
            </div>
          </div>

          <!-- Profile Details -->
          <div class="hero-details-col">
            <div class="hero-name-row">
              <h1 class="hero-name">{{ portfolio?.profile?.fullName || 'Lokesh Pon' }}</h1>
              <div class="hero-role-pill">
                <i class="fa-solid fa-helmet-safety"></i>
                <span>{{ portfolio?.profile?.role || 'Civil Engineering Student' }}</span>
              </div>
            </div>

            <!-- College Name and Location -->
            <div class="hero-college-location">
              <span>
                <i class="fa-solid fa-building-columns" style="color: var(--primary);"></i>
                <strong>{{ portfolio?.profile?.college || 'Government College of Engineering (IRTT), Erode' }}</strong>
              </span>
              <span>
                <i class="fa-solid fa-location-dot" style="color: var(--accent);"></i>
                {{ portfolio?.profile?.collegeLocation || 'Erode, Tamil Nadu' }}
              </span>
              <span>
                <i class="fa-solid fa-house-user" style="color: #38bdf8;"></i>
                Native: {{ portfolio?.profile?.nativeLocation || 'Ramanathapuram, Tamil Nadu' }}
              </span>
            </div>

            <!-- Short Professional Tagline -->
            <p class="hero-tagline">
              "{{ portfolio?.profile?.tagline || 'Transforming architectural visions into precision 2D drawings, 3D photorealistic BIM models, and resilient structural designs.' }}"
            </p>

            <!-- Mandatory Buttons: Download Resume & Contact Me -->
            <div class="hero-cta-group">
              <button class="btn btn-primary" @click="downloadResume('resume')">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Download Resume (PDF)</span>
              </button>
              
              <button class="btn btn-outline" @click="downloadResume('portfolio')">
                <i class="fa-solid fa-book-open"></i>
                <span>View Full Portfolio (PDF)</span>
              </button>

              <button class="btn btn-accent" @click="scrollToSection('contact')">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Contact Me</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Quick Stat Metrics -->
        <div class="quick-stats-grid">
          <div v-for="(stat, idx) in portfolio?.quickStats" :key="idx" class="stat-card glass-panel">
            <div class="stat-value">{{ stat.value }}</div>
            <div class="stat-label">{{ stat.label }}</div>
            <div class="stat-detail">{{ stat.detail }}</div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ==========================================
       2. ABOUT ME SECTION (MANDATORY)
       ========================================== -->
  <section id="about" class="section-padding">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-user"></i>
        <span>Section 02</span>
      </div>
      <h2 class="section-title">About Me</h2>
      <p class="section-subtitle">Bridging architectural creativity and civil structural engineering with Building Information Modeling (BIM) precision.</p>

      <div class="about-grid">
        
        <!-- Left Column: Biography & Meta -->
        <div>
          <div class="about-text-content glass-panel" style="padding: 32px;">
            <p>
              {{ portfolio?.about?.summary }}
            </p>

            <div class="about-meta-grid">
              <div class="meta-box">
                <div class="meta-box-label">Current Academic Year</div>
                <div class="meta-box-value">{{ portfolio?.about?.academicYear }}</div>
              </div>
              <div class="meta-box">
                <div class="meta-box-label">College & University</div>
                <div class="meta-box-value">{{ portfolio?.about?.institution }}</div>
              </div>
              <div class="meta-box">
                <div class="meta-box-label">Affiliation</div>
                <div class="meta-box-value">{{ portfolio?.about?.university }}</div>
              </div>
              <div class="meta-box">
                <div class="meta-box-label">Core Specialization</div>
                <div class="meta-box-value" style="color: var(--primary);">BIM & Structural Modeling</div>
              </div>
            </div>

            <!-- Career Objectives -->
            <div class="career-objective-card">
              <h4>
                <i class="fa-solid fa-crosshairs"></i>
                <span>Career Objectives</span>
              </h4>
              <p style="font-size: 0.92rem; color: var(--text-primary); line-height: 1.6;">
                {{ portfolio?.about?.careerObjectives }}
              </p>
            </div>
          </div>

          <!-- Personal Strengths -->
          <div style="margin-top: 24px;">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-award" style="color: var(--accent);"></i>
              <span>Personal Strengths</span>
            </h3>
            <div class="strengths-grid">
              <div v-for="(strength, sIdx) in portfolio?.about?.personalStrengths" :key="sIdx" class="strength-pill">
                <div class="strength-pill-name">✓ {{ strength.name }}</div>
                <div class="strength-pill-desc">{{ strength.desc }}</div>
              </div>
            </div>
          </div>

        </div>

        <!-- Right Column: Areas of Interest in Civil Engineering -->
        <div>
          <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-shapes" style="color: var(--primary);"></i>
            <span>Areas of Interest in Civil Engineering</span>
          </h3>

          <div class="areas-list">
            <div v-for="(area, aIdx) in portfolio?.about?.areasOfInterest" :key="aIdx" class="area-card glass-panel">
              <div class="area-icon-box">
                <i v-if="area.icon === 'building'" class="fa-solid fa-building"></i>
                <i v-else-if="area.icon === 'cube'" class="fa-solid fa-cube"></i>
                <i v-else-if="area.icon === 'cpu'" class="fa-solid fa-network-wired"></i>
                <i v-else class="fa-solid fa-layer-group"></i>
              </div>
              <div class="area-card-body">
                <h4>{{ area.title }}</h4>
                <p>{{ area.desc }}</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ==========================================
       3. EDUCATION SECTION (MANDATORY)
       ========================================== -->
  <section id="education" class="section-padding" style="background: rgba(15, 23, 42, 0.4);">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>Section 03</span>
      </div>
      <h2 class="section-title">Education</h2>
      <p class="section-subtitle">Academic milestones, continuous coursework, and technical training in Civil Engineering.</p>

      <div class="education-timeline">
        <div v-for="(edu, eIdx) in portfolio?.education" :key="eIdx" class="education-card glass-panel" :class="{ 'featured': eIdx === 0 }">
          
          <div class="edu-main-info">
            <h3>{{ edu.degree }}</h3>
            <div class="edu-institution">
              <i class="fa-solid fa-school"></i>
              <span>{{ edu.institution }}</span>
            </div>

            <div class="edu-meta">
              <span><i class="fa-solid fa-location-dot"></i> {{ edu.location }}</span>
              <span><i class="fa-solid fa-calendar"></i> {{ edu.period }}</span>
              <span v-if="edu.status" style="color: var(--primary); font-weight: 700;"><i class="fa-solid fa-circle-check"></i> {{ edu.status }}</span>
            </div>

            <p class="edu-desc">{{ edu.description }}</p>

            <!-- Coursework Chips -->
            <div v-if="edu.courses && edu.courses.length" class="course-tags">
              <span v-for="(c, cIdx) in edu.courses" :key="cIdx" class="course-tag">{{ c }}</span>
            </div>
          </div>

          <!-- Score Badge -->
          <div class="edu-score-badge">
            <div class="score-num">{{ edu.score }}</div>
            <div class="score-label">{{ edu.scoreType }}</div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       4. TECHNICAL SKILLS SECTION (MANDATORY)
       ========================================== -->
  <section id="skills" class="section-padding">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        <span>Section 04</span>
      </div>
      <h2 class="section-title">Technical Skills</h2>
      <p class="section-subtitle">Proficiency across verified architectural modeling software, structural analysis, and field engineering methodologies.</p>

      <!-- Skill Filter Tabs -->
      <div class="skills-filter-nav">
        <button class="skill-tab-btn" :class="{ 'active': activeSkillTab === 'all' }" @click="activeSkillTab = 'all'">
          All Skills
        </button>
        <button class="skill-tab-btn" :class="{ 'active': activeSkillTab === 'software' }" @click="activeSkillTab = 'software'">
          Software & Tools
        </button>
        <button class="skill-tab-btn" :class="{ 'active': activeSkillTab === 'engineering' }" @click="activeSkillTab = 'engineering'">
          Field & Engineering Core
        </button>
      </div>

      <div class="skills-wrapper">
        
        <!-- Software Skills Grid -->
        <div v-if="activeSkillTab === 'all' || activeSkillTab === 'software'">
          <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-laptop-code" style="color: var(--primary);"></i>
            <span>Software & BIM Tools</span>
          </h3>

          <div class="software-skills-grid">
            <div v-for="(sw, swIdx) in portfolio?.skills?.software" :key="swIdx" class="software-skill-card glass-panel">
              <div class="skill-header">
                <span class="skill-name">{{ sw.name }}</span>
                <span class="skill-badge">{{ sw.badge }}</span>
              </div>
              <div class="skill-category">{{ sw.category }}</div>

              <div class="progress-track">
                <div class="progress-fill" :style="{ width: sw.percentage + '%' }"></div>
              </div>

              <p class="skill-notes">{{ sw.notes }}</p>
            </div>
          </div>
        </div>

        <!-- Field & Practical Engineering Competencies -->
        <div v-if="activeSkillTab === 'all' || activeSkillTab === 'engineering'">
          <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-compass-drafting" style="color: var(--accent);"></i>
            <span>Practical & Core Civil Competencies</span>
          </h3>

          <div class="engineering-competencies-grid">
            <div v-for="(eng, engIdx) in portfolio?.skills?.engineering" :key="engIdx" class="eng-comp-card glass-panel">
              <h4>{{ eng.name }}</h4>
              <p>{{ eng.desc }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ==========================================
       5. PROJECTS SECTION (VERY IMPORTANT)
       ========================================== -->
  <section id="projects" class="section-padding" style="background: rgba(15, 23, 42, 0.5);">
    <div class="container">
      <div class="section-badge" style="background: var(--accent-light); color: var(--accent); border-color: rgba(245, 158, 11, 0.4);">
        <i class="fa-solid fa-star"></i>
        <span>Section 05 • Very Important</span>
      </div>
      <h2 class="section-title">Featured Engineering Projects</h2>
      <p class="section-subtitle">In-depth case studies featuring problem statements, methodology, tool integration, and high-fidelity 3D visual outputs.</p>

      <!-- Project Filter Pills -->
      <div class="skills-filter-nav" style="justify-content: flex-start; margin-bottom: 30px;">
        <button class="skill-tab-btn" :class="{ 'active': activeProjectFilter === 'all' }" @click="activeProjectFilter = 'all'">
          All Projects ({{ portfolio?.projects?.length || 4 }})
        </button>
        <button class="skill-tab-btn" :class="{ 'active': activeProjectFilter === 'SketchUp' }" @click="activeProjectFilter = 'SketchUp'">
          SketchUp + Enscape
        </button>
        <button class="skill-tab-btn" :class="{ 'active': activeProjectFilter === 'AutoCAD' }" @click="activeProjectFilter = 'AutoCAD'">
          AutoCAD 2D Drafting
        </button>
        <button class="skill-tab-btn" :class="{ 'active': activeProjectFilter === 'Revit' }" @click="activeProjectFilter = 'Revit'">
          Autodesk Revit BIM
        </button>
        <button class="skill-tab-btn" :class="{ 'active': activeProjectFilter === 'STAAD' }" @click="activeProjectFilter = 'STAAD'">
          STAAD.Pro Analysis
        </button>
      </div>

      <!-- Projects Grid -->
      <div class="projects-grid">
        <div v-for="(project, pIdx) in filteredProjects" :key="project.id" class="project-card glass-panel">
          
          <div class="project-img-box">
            <img :src="project.coverImage" :alt="project.title" class="project-img">
            <span class="project-badge-overlay">{{ project.category }}</span>
          </div>

          <div class="project-body">
            <div class="project-tools">
              <span v-for="(tool, tIdx) in project.software" :key="tIdx" class="tool-pill">{{ tool }}</span>
            </div>

            <h3 class="project-title">{{ project.title }}</h3>
            <p class="project-short-desc">{{ project.shortDesc }}</p>

            <div class="project-footer">
              <button class="view-study-btn" @click="openProjectModal(project)">
                <span>View Full Case Study</span>
                <i class="fa-solid fa-arrow-right"></i>
              </button>

              <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                <i class="fa-solid fa-images"></i> {{ project.gallery?.length || 1 }} Renders
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       6. INTERNSHIP & INDUSTRIAL TRAINING
       ========================================== -->
  <section id="internships" class="section-padding">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-trowel-bricks"></i>
        <span>Section 06</span>
      </div>
      <h2 class="section-title">Internship & Industrial Training</h2>
      <p class="section-subtitle">Real-world site supervision experience, pile foundation works in Chennai, and building execution in Ramanathapuram.</p>

      <div class="internships-grid">
        <div v-for="(intern, iIdx) in portfolio?.internships" :key="iIdx" class="internship-card glass-panel">
          
          <div class="intern-header">
            <div>
              <h3 class="intern-company">{{ intern.company }}</h3>
              <div class="intern-role">{{ intern.role }}</div>
            </div>
            <span class="intern-badge">{{ intern.badge }}</span>
          </div>

          <div class="intern-meta">
            <span><i class="fa-solid fa-location-dot"></i> {{ intern.location }}</span>
            <span><i class="fa-solid fa-clock"></i> {{ intern.duration }}</span>
            <span><i class="fa-solid fa-layer-group"></i> {{ intern.domain }}</span>
          </div>

          <div class="intern-tasks-title">Key Responsibilities & Tasks Performed:</div>
          <ul class="intern-tasks-list">
            <li v-for="(task, tIdx) in intern.tasks" :key="tIdx">{{ task }}</li>
          </ul>

          <div class="intern-knowledge-box">
            <strong>Practical Knowledge Gained:</strong>
            <p>{{ intern.knowledge }}</p>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       7. CERTIFICATIONS & COURSES
       ========================================== -->
  <section id="certifications" class="section-padding" style="background: rgba(15, 23, 42, 0.4);">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-certificate"></i>
        <span>Section 07</span>
      </div>
      <h2 class="section-title">Certifications & Courses</h2>
      <p class="section-subtitle">Industry-recognized CAD certifications, BIM modeling courses, and structural software workshops.</p>

      <div class="certifications-grid">
        <div v-for="(cert, cIdx) in portfolio?.certifications" :key="cIdx" class="cert-card glass-panel" :class="{ 'featured-cert': cert.verified && cert.image }">
          
          <div class="cert-top-row">
            <span class="cert-badge">
              <i class="fa-solid fa-circle-check"></i>
              <span>{{ cert.type }}</span>
            </span>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
              <i class="fa-solid fa-stopwatch"></i> {{ cert.duration }}
            </span>
          </div>

          <h3 class="cert-title">{{ cert.title }}</h3>
          <div class="cert-issuer">
            <i class="fa-solid fa-building-circle-check"></i> {{ cert.issuer }}
          </div>

          <div class="cert-meta-list">
            <span><i class="fa-solid fa-hashtag"></i> <strong>Credential ID:</strong> {{ cert.certificateNo }}</span>
            <span v-if="cert.instructor"><i class="fa-solid fa-user-tie"></i> <strong>Instructor:</strong> {{ cert.instructor }}</span>
            <span><i class="fa-solid fa-calendar-check"></i> <strong>Date:</strong> {{ cert.date }}</span>
          </div>

          <p class="cert-desc">{{ cert.description }}</p>

          <div class="cert-actions" v-if="cert.image">
            <button class="btn btn-primary" style="width: 100%;" @click="openCertModal(cert)">
              <i class="fa-solid fa-magnifying-glass-plus"></i>
              <span>View Autodesk Certificate</span>
            </button>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       8. PRACTICAL & FIELD EXPERIENCE
       ========================================== -->
  <section id="field-work" class="section-padding">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-compass"></i>
        <span>Section 08</span>
      </div>
      <h2 class="section-title">Practical & Field Experience</h2>
      <p class="section-subtitle">Hands-on experience across civil engineering laboratories, site leveling, soil/concrete testing, and estimation.</p>

      <div class="field-grid">
        <div v-for="(f, fIdx) in portfolio?.fieldExperience" :key="fIdx" class="field-card glass-panel">
          <div class="field-icon-wrapper">
            <i v-if="f.icon === 'compass'" class="fa-solid fa-compass"></i>
            <i v-else-if="f.icon === 'layers'" class="fa-solid fa-layer-group"></i>
            <i v-else-if="f.icon === 'shield-check'" class="fa-solid fa-cubes"></i>
            <i v-else-if="f.icon === 'anchor'" class="fa-solid fa-trowel"></i>
            <i v-else-if="f.icon === 'flask-conical'" class="fa-solid fa-flask-vial"></i>
            <i v-else class="fa-solid fa-calculator"></i>
          </div>

          <h3 class="field-title">{{ f.title }}</h3>
          <div class="field-instrument">
            <i class="fa-solid fa-wrench"></i> {{ f.instrument }}
          </div>
          <p class="field-desc">{{ f.desc }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       9. ACHIEVEMENTS & ACTIVITIES
       ========================================== -->
  <section id="achievements" class="section-padding" style="background: rgba(15, 23, 42, 0.4);">
    <div class="container">
      <div class="section-badge">
        <i class="fa-solid fa-trophy"></i>
        <span>Section 09</span>
      </div>
      <h2 class="section-title">Achievements & Activities</h2>
      <p class="section-subtitle">Technical paper presentations, inter-college symposiums, and distinguished state/international yoga championships.</p>

      <div class="achievements-grid">
        <div v-for="(ach, aIdx) in portfolio?.achievements" :key="aIdx" class="achievement-card glass-panel">
          <span class="achieve-badge">{{ ach.badge }}</span>
          
          <h3 class="achieve-title">{{ ach.title }}</h3>
          <div class="achieve-org">
            <i class="fa-solid fa-landmark"></i> {{ ach.organization }}
          </div>

          <div class="achieve-topic">
            <i class="fa-solid fa-bullhorn"></i> {{ ach.topic }}
          </div>

          <p class="achieve-desc">{{ ach.desc }}</p>

          <div class="achieve-highlight">
            <i class="fa-solid fa-medal"></i> {{ ach.highlight }}
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================
       10. CONTACT SECTION (MANDATORY)
       ========================================== -->
  <section id="contact" class="section-padding">
    <div class="container">
      <div class="section-badge" style="background: var(--primary-light); color: var(--primary);">
        <i class="fa-solid fa-address-card"></i>
        <span>Section 10 • Mandatory</span>
      </div>
      <h2 class="section-title">Get In Touch</h2>
      <p class="section-subtitle">Feel free to reach out for structural/BIM opportunities, campus placements, project inquiries, or collaborations.</p>

      <div class="contact-wrapper">
        
        <!-- Left: Contact Details & Resume Downloads -->
        <div class="contact-info-panel">
          
          <!-- Email -->
          <div class="contact-tile glass-panel">
            <div class="contact-tile-icon">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="contact-tile-body">
              <label>Email Address</label>
              <a :href="'mailto:' + portfolio?.contact?.email">{{ portfolio?.contact?.email }}</a>
            </div>
          </div>

          <!-- Mobile Phone -->
          <div class="contact-tile glass-panel">
            <div class="contact-tile-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
              <i class="fa-solid fa-phone"></i>
            </div>
            <div class="contact-tile-body">
              <label>Mobile Number / WhatsApp</label>
              <a :href="'tel:' + portfolio?.contact?.phone">{{ portfolio?.contact?.phoneFormatted }}</a>
            </div>
          </div>

          <!-- LinkedIn -->
          <div class="contact-tile glass-panel">
            <div class="contact-tile-icon" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
              <i class="fa-brands fa-linkedin-in"></i>
            </div>
            <div class="contact-tile-body">
              <label>LinkedIn Profile</label>
              <a :href="portfolio?.contact?.linkedin" target="_blank" rel="noopener noreferrer">
                {{ portfolio?.contact?.linkedinHandle }}
              </a>
            </div>
          </div>

          <!-- Location -->
          <div class="contact-tile glass-panel">
            <div class="contact-tile-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
              <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div class="contact-tile-body">
              <label>Location</label>
              <p>{{ portfolio?.contact?.location || 'Ramanathapuram-623501,TamilNadu' }}</p>
            </div>
          </div>

          <!-- Resume & Portfolio Downloads -->
          <div class="resume-download-box glass-panel">
            <h4>
              <i class="fa-solid fa-download" style="color: var(--accent);"></i>
              <span>Download Documents</span>
            </h4>
            <p>Directly download official copies of Lokesh Pon's civil engineering resume and full portfolio document.</p>

            <div class="resume-btn-group">
              <button class="btn btn-primary" @click="downloadResume('resume')">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Download Resume</span>
              </button>
              
              <button class="btn btn-outline" @click="downloadResume('portfolio')">
                <i class="fa-solid fa-file-lines"></i>
                <span>Download Portfolio</span>
              </button>
            </div>
          </div>

        </div>

        <!-- Right: Interactive Contact Form (Handled by PHP) -->
        <div class="contact-form-panel glass-panel">
          <h3 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 8px;">Send a Direct Message</h3>
          <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 24px;">
            Submit your inquiry below. Messages are saved securely and delivered straight to Lokesh Pon.
          </p>

          <!-- Feedback Status Alert -->
          <div v-if="contactStatus" class="alert-message" :class="contactStatus.success ? 'alert-success' : 'alert-error'">
            <i :class="contactStatus.success ? 'fa-solid fa-circle-check' : 'fa-solid fa-triangle-exclamation'"></i>
            <span>{{ contactStatus.message }}</span>
          </div>

          <form @submit.prevent="submitContact">
            
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Your Name *</label>
                <input type="text" v-model="contactForm.name" class="form-input" placeholder="e.g. John Doe" required>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" v-model="contactForm.email" class="form-input" placeholder="name@company.com" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Phone Number (Optional)</label>
                <input type="tel" v-model="contactForm.phone" class="form-input" placeholder="+91 9876543210">
              </div>

              <div class="form-group">
                <label class="form-label">Subject</label>
                <input type="text" v-model="contactForm.subject" class="form-input" placeholder="Civil Engineering / BIM Opportunity">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Your Message *</label>
              <textarea v-model="contactForm.message" class="form-textarea" placeholder="Write your project details or message here..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;" :disabled="contactSubmitting">
              <i v-if="contactSubmitting" class="fa-solid fa-spinner fa-spin"></i>
              <i v-else class="fa-solid fa-paper-plane"></i>
              <span>{{ contactSubmitting ? 'Sending Message...' : 'Send Message' }}</span>
            </button>

          </form>
        </div>

      </div>
    </div>
  </section>

  <!-- ==========================================
       SITE FOOTER
       ========================================== -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-content">
        <div class="nav-brand">
          <div class="nav-logo-icon">
            <i class="fa-solid fa-compass-drafting"></i>
          </div>
          <div class="nav-brand-text">
            <h1>{{ portfolio?.profile?.fullName || 'LOKESH PON' }}</h1>
            <span>Civil Engineering Student • Government College of Engineering (IRTT), Erode</span>
          </div>
        </div>

        <div style="display: flex; gap: 12px;">
          <a :href="'mailto:' + portfolio?.contact?.email" class="icon-btn" title="Email"><i class="fa-solid fa-envelope"></i></a>
          <a :href="'tel:' + portfolio?.contact?.phone" class="icon-btn" title="Phone"><i class="fa-solid fa-phone"></i></a>
          <a :href="portfolio?.contact?.linkedin" target="_blank" class="icon-btn" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>

      <div class="footer-copy">
        © <?php echo date('Y'); ?> Lokesh Pon. Built with modern PHP & Vue.js. All architectural 3D models and drafting drawings copyrighted.
      </div>
    </div>
  </footer>

  <!-- ==========================================
       PROJECT CASE STUDY MODAL
       ========================================== -->
  <div v-if="selectedProject" class="modal-overlay" @click.self="closeProjectModal">
    <div class="modal-dialog">
      <button class="modal-close-btn" @click="closeProjectModal">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <div class="modal-header">
        <span class="section-badge">{{ selectedProject.category }}</span>
        <h2 style="font-size: 1.6rem; font-weight: 800; margin-top: 6px;">{{ selectedProject.title }}</h2>
        <div class="project-tools" style="margin-top: 10px;">
          <span v-for="(tool, idx) in selectedProject.software" :key="idx" class="tool-pill">{{ tool }}</span>
        </div>
      </div>

      <div class="modal-body">
        
        <!-- Problem Statement -->
        <div class="study-section">
          <div class="study-heading">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>Problem Statement</span>
          </div>
          <p class="study-text">{{ selectedProject.problemStatement }}</p>
        </div>

        <!-- Objectives -->
        <div class="study-section">
          <div class="study-heading">
            <i class="fa-solid fa-bullseye"></i>
            <span>Project Objectives</span>
          </div>
          <ul class="intern-tasks-list" style="margin-top: 8px;">
            <li v-for="(obj, oIdx) in selectedProject.objectives" :key="oIdx">{{ obj }}</li>
          </ul>
        </div>

        <!-- Methodology -->
        <div class="study-section">
          <div class="study-heading">
            <i class="fa-solid fa-diagram-project"></i>
            <span>Methodology & Technical Workflow</span>
          </div>
          <p class="study-text" style="white-space: pre-line;">{{ selectedProject.methodology }}</p>
        </div>

        <!-- Software Used -->
        <div class="study-section">
          <div class="study-heading">
            <i class="fa-solid fa-laptop-code"></i>
            <span>Software & Tools Utilized</span>
          </div>
          <div class="project-tools" style="margin-top: 8px;">
            <span v-for="(tool, idx) in selectedProject.software" :key="idx" class="tool-pill" style="font-size: 0.82rem; padding: 5px 12px;">{{ tool }}</span>
          </div>
        </div>

        <!-- His Contribution -->
        <div class="study-section">
          <div class="study-heading">
            <i class="fa-solid fa-user-check"></i>
            <span>Lokesh's Direct Contribution</span>
          </div>
          <p class="study-text">{{ selectedProject.contribution }}</p>
        </div>

        <!-- Photographs & Drawings Gallery -->
        <div class="study-section">
          <div class="study-heading">
            <i class="fa-solid fa-images"></i>
            <span>Project Photographs & Drawings Gallery (Click to Enlarge)</span>
          </div>
          
          <div class="study-gallery-grid">
            <div v-for="(img, gIdx) in selectedProject.gallery" :key="gIdx" class="study-thumb-box" @click="openLightbox(img.src, img.caption)">
              <img :src="img.src" :alt="img.caption">
            </div>
          </div>
        </div>

        <!-- Final Outcome -->
        <div class="study-section" style="margin-bottom: 0;">
          <div class="study-heading" style="color: var(--accent);">
            <i class="fa-solid fa-circle-check"></i>
            <span>Final Outcome</span>
          </div>
          <p class="study-text" style="font-weight: 600; color: var(--text-primary);">{{ selectedProject.outcome }}</p>
        </div>

        <!-- Attached Report / Document (if available) -->
        <div v-if="selectedProject.reportPdf" class="study-section" style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-color);">
          <div class="study-heading" style="color: var(--primary);">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Structural Analysis Report (STAAD.Pro)</span>
          </div>
          <!-- Inline PDF Preview -->
          <iframe
            :src="selectedProject.reportPdf + '&inline=1'"
            class="study-pdf-viewer"
            title="STAAD.Pro Structural Analysis Report"
          ></iframe>
          <!-- Download Button -->
          <div style="margin-top: 14px;">
            <a :href="selectedProject.reportPdf" class="btn btn-primary btn-study-download" download target="_blank">
              <i class="fa-solid fa-file-arrow-down"></i>
              <span>Download Full Structural Analysis Report (PDF)</span>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ==========================================
       CERTIFICATE MODAL
       ========================================== -->
  <div v-if="selectedCert" class="modal-overlay" @click.self="closeCertModal">
    <div class="modal-dialog" style="max-width: 820px;">
      <button class="modal-close-btn" @click="closeCertModal">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <div class="modal-header">
        <span class="section-badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
          <i class="fa-solid fa-award"></i> Verified Autodesk Credential
        </span>
        <h2 style="font-size: 1.4rem; font-weight: 800; margin-top: 6px;">{{ selectedCert.title }}</h2>
        <p style="color: var(--text-secondary); font-size: 0.88rem;">Issued by {{ selectedCert.issuer }}</p>
      </div>

      <div class="modal-body" style="text-align: center;">
        <img :src="selectedCert.image" :alt="selectedCert.title" style="width: 100%; border-radius: var(--radius-sm); border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
        
        <div style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
          <div style="text-align: left;">
            <div style="font-size: 0.82rem; color: var(--text-muted);">Certificate Number</div>
            <div style="font-family: var(--font-mono); font-weight: 700; color: var(--primary);">{{ selectedCert.certificateNo }}</div>
          </div>
          <button class="btn btn-outline" @click="openLightbox(selectedCert.image, selectedCert.title)">
            <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
            <span>Full-screen Zoom</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       COVER PHOTO & AVATAR UPLOAD MODAL
       ========================================== -->
  <div v-if="coverModalOpen" class="modal-overlay" @click.self="closeUploadModal">
    <div class="modal-dialog" style="max-width: 600px;">
      <button class="modal-close-btn" @click="closeUploadModal">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <div class="modal-header">
        <span class="section-badge">
          <i class="fa-solid fa-upload"></i> Live Media Upload
        </span>
        <h2 style="font-size: 1.35rem; font-weight: 800; margin-top: 6px;">
          {{ uploadType === 'avatar' ? 'Upload Profile Photo' : 'Upload Cover Photo' }}
        </h2>
        <p style="color: var(--text-secondary); font-size: 0.88rem;">
          Select a photo from your computer. The PHP backend will save it and update your portfolio live!
        </p>
      </div>

      <div class="modal-body">
        
        <!-- Toggle Upload Type -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
          <button class="btn" :class="uploadType === 'coverPhoto' ? 'btn-primary' : 'btn-outline'" @click="uploadType = 'coverPhoto'" style="flex: 1;">
            <i class="fa-solid fa-image"></i>
            <span>Cover Photo</span>
          </button>
          <button class="btn" :class="uploadType === 'avatar' ? 'btn-primary' : 'btn-outline'" @click="uploadType = 'avatar'" style="flex: 1;">
            <i class="fa-solid fa-user"></i>
            <span>Profile Photo</span>
          </button>
        </div>

        <!-- Drop Zone / File Input -->
        <label class="upload-drop-zone" style="display: block;">
          <input type="file" accept="image/jpeg,image/png,image/webp" @change="onFileSelected" style="display: none;">
          <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 12px; display: block;"></i>
          <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary); display: block;">Click to browse or drop photo here</span>
          <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 4px;">Supports JPG, PNG, WEBP (Max 15MB)</span>
        </label>

        <!-- Preview -->
        <div v-if="previewUrl" class="upload-preview-box">
          <img :src="previewUrl" alt="Upload Preview">
        </div>

        <!-- Alert messages -->
        <div v-if="uploadError" class="alert-message alert-error" style="margin-top: 16px;">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span>{{ uploadError }}</span>
        </div>

        <div v-if="uploadMessage" class="alert-message alert-success" style="margin-top: 16px;">
          <i class="fa-solid fa-circle-check"></i>
          <span>{{ uploadMessage }}</span>
        </div>

        <!-- Upload Action Button -->
        <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
          <button class="btn btn-outline" @click="closeUploadModal">Cancel</button>
          <button class="btn btn-primary" :disabled="!selectedFile || isUploading" @click="submitUpload">
            <i v-if="isUploading" class="fa-solid fa-spinner fa-spin"></i>
            <i v-else class="fa-solid fa-upload"></i>
            <span>{{ isUploading ? 'Uploading to Server...' : 'Apply Photo' }}</span>
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- ==========================================
       FULLSCREEN LIGHTBOX
       ========================================== -->
  <div v-if="lightboxImage" class="lightbox-overlay" @click.self="closeLightbox">
    <button class="modal-close-btn" style="top: 24px; right: 24px;" @click="closeLightbox">
      <i class="fa-solid fa-xmark"></i>
    </button>
    <img :src="lightboxImage.src" :alt="lightboxImage.caption" class="lightbox-img">
    <div class="lightbox-caption">{{ lightboxImage.caption }}</div>
  </div>

</div>

<!-- Vue 3 JS (Local bundle with CDN fallback) -->
<script src="assets/js/vue.global.prod.js"></script>
<script>
  if (typeof Vue === 'undefined') {
    document.write('<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"><\/script>');
  }
</script>

<!-- Main Vue 3 Application Script -->
<script src="assets/js/app.js"></script>
</body>
</html>
