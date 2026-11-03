<?php
/**
 * Lokesh Pon - Portfolio Inquiries Dashboard
 * View, search, manage, and export customer and recruiter inquiries.
 */

$inquiriesFile = __DIR__ . '/data/inquiries.json';
$inquiries = [];

if (file_exists($inquiriesFile)) {
    $raw = file_get_contents($inquiriesFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $inquiries = $decoded;
    }
}

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=lokesh_portfolio_inquiries_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($output, ['ID', 'Date & Time', 'Full Name', 'Email', 'Phone', 'Subject', 'Message', 'IP Address']);
    foreach ($inquiries as $inq) {
        fputcsv($output, [
            $inq['id'] ?? '',
            $inq['submittedAt'] ?? '',
            $inq['name'] ?? '',
            $inq['email'] ?? '',
            $inq['phone'] ?? '',
            $inq['subject'] ?? '',
            $inq['message'] ?? '',
            $inq['ipAddress'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Handle Delete Inquiry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['id'])) {
    $deleteId = $_POST['id'];
    $filtered = array_filter($inquiries, function($item) use ($deleteId) {
        return ($item['id'] ?? '') !== $deleteId;
    });
    $inquiries = array_values($filtered);
    file_put_contents($inquiriesFile, json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    header('Location: inquiries.php?deleted=1');
    exit;
}

$totalCount = count($inquiries);
$todayDate = date('Y-m-d');
$todayCount = 0;
$uniqueEmails = [];

foreach ($inquiries as $inq) {
    if (!empty($inq['submittedAt']) && strpos($inq['submittedAt'], $todayDate) === 0) {
        $todayCount++;
    }
    if (!empty($inq['email'])) {
        $uniqueEmails[$inq['email']] = true;
    }
}
$uniqueCount = count($uniqueEmails);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inquiries Dashboard • Lokesh Pon</title>
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body { padding-top: 40px; padding-bottom: 60px; min-height: 100vh; }
    .inquiry-badge { font-size: 0.75rem; padding: 3px 8px; border-radius: var(--radius-full); background: var(--primary-light); color: var(--primary); font-weight: 700; }
    .table-container { overflow-x: auto; margin-top: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-card); }
    .inquiries-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
    .inquiries-table th { padding: 14px 18px; background: rgba(15, 23, 42, 0.6); color: var(--text-secondary); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border-color); }
    [data-theme="light"] .inquiries-table th { background: #f1f5f9; }
    .inquiries-table td { padding: 16px 18px; border-bottom: 1px solid var(--border-color); vertical-align: top; }
    .inquiries-table tr:hover { background: rgba(2, 132, 199, 0.04); }
    .msg-preview { max-width: 320px; white-space: pre-wrap; font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5; }
    .search-bar { width: 100%; max-width: 380px; padding: 10px 16px; border-radius: var(--radius-sm); background: var(--bg-input); border: 1px solid var(--border-color); color: var(--text-primary); font-family: inherit; font-size: 0.9rem; }
    .search-bar:focus { outline: none; border-color: var(--primary); }
    .btn-delete { background: none; border: none; color: #ef4444; cursor: pointer; padding: 6px 10px; border-radius: var(--radius-sm); transition: var(--transition); }
    .btn-delete:hover { background: rgba(239, 68, 68, 0.15); }
  </style>
</head>
<body>

  <div class="container" style="max-width: 1320px;">
    
    <!-- Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color);">
      <div>
        <a href="index.php" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.82rem; margin-bottom: 12px;">
          <i class="fa-solid fa-arrow-left"></i>
          <span>Back to Portfolio</span>
        </a>
        <h1 style="font-size: 1.85rem; font-weight: 800; display: flex; align-items: center; gap: 12px;">
          <i class="fa-solid fa-inbox" style="color: var(--primary);"></i>
          <span>Client Inquiries Dashboard</span>
        </h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 4px;">
          Manage and review all contact form messages received from potential employers and clients.
        </p>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <button id="themeToggleBtn" class="icon-btn" title="Toggle Dark/Light Mode">
          <i id="themeIcon" class="fa-solid fa-sun"></i>
        </button>
        <a href="inquiries.php?export=csv" class="btn btn-primary" title="Export all submissions to Excel / CSV">
          <i class="fa-solid fa-file-excel"></i>
          <span>Export to Excel (CSV)</span>
        </a>
      </div>
    </div>

    <!-- Metric Cards -->
    <div class="quick-stats-grid" style="margin-top: 0; margin-bottom: 28px;">
      <div class="stat-card glass-panel">
        <div class="stat-value"><?php echo $totalCount; ?></div>
        <div class="stat-label">Total Inquiries</div>
        <div class="stat-detail">Lifetime messages received</div>
      </div>
      <div class="stat-card glass-panel">
        <div class="stat-value" style="color: var(--accent);"><?php echo $todayCount; ?></div>
        <div class="stat-label">Received Today</div>
        <div class="stat-detail"><?php echo date('M d, Y'); ?></div>
      </div>
      <div class="stat-card glass-panel">
        <div class="stat-value" style="color: #10b981;"><?php echo $uniqueCount; ?></div>
        <div class="stat-label">Unique Contacts</div>
        <div class="stat-detail">Different email senders</div>
      </div>
    </div>

    <!-- Controls Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 16px;">
      <input type="text" id="searchInput" class="search-bar" placeholder="🔍 Search by name, email, or message keyword...">
      <div style="font-size: 0.85rem; color: var(--text-muted);">
        Storage: <code style="color: var(--primary);">data/inquiries.json</code> &bull; Email: <code style="color: var(--accent);">lokeshpon2005@gmail.com</code>
      </div>
    </div>

    <!-- Table of Inquiries -->
    <div class="table-container glass-panel">
      <?php if ($totalCount === 0): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
          <i class="fa-regular fa-folder-open" style="font-size: 3rem; margin-bottom: 14px; opacity: 0.5;"></i>
          <h3>No inquiries received yet</h3>
          <p style="font-size: 0.9rem; margin-top: 6px;">When visitors fill out the contact form on your portfolio, their messages will appear here.</p>
        </div>
      <?php else: ?>
        <table class="inquiries-table" id="inquiriesTable">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Sender</th>
              <th>Contact Details</th>
              <th>Subject & Message</th>
              <th style="text-align: right;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inquiries as $inq): ?>
              <tr>
                <td style="white-space: nowrap; font-size: 0.82rem; color: var(--text-muted);">
                  <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                  <?php echo htmlspecialchars($inq['submittedAt'] ?? 'Unknown'); ?>
                </td>
                <td>
                  <strong style="color: var(--text-primary); font-size: 0.95rem;">
                    <?php echo htmlspecialchars($inq['name'] ?? 'Anonymous'); ?>
                  </strong>
                </td>
                <td>
                  <div>
                    <a href="mailto:<?php echo urlencode($inq['email'] ?? ''); ?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                      <i class="fa-solid fa-envelope" style="font-size: 0.78rem;"></i> <?php echo htmlspecialchars($inq['email'] ?? 'No email'); ?>
                    </a>
                  </div>
                  <?php if (!empty($inq['phone'])): ?>
                    <div style="margin-top: 4px;">
                      <a href="tel:<?php echo urlencode($inq['phone']); ?>" style="color: var(--text-secondary); text-decoration: none; font-size: 0.82rem;">
                        <i class="fa-solid fa-phone" style="font-size: 0.75rem;"></i> <?php echo htmlspecialchars($inq['phone']); ?>
                      </a>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                    <?php echo htmlspecialchars($inq['subject'] ?? 'No Subject'); ?>
                  </div>
                  <div class="msg-preview">
                    <?php echo nl2br(htmlspecialchars($inq['message'] ?? '')); ?>
                  </div>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  <a href="mailto:<?php echo urlencode($inq['email'] ?? ''); ?>?subject=Re:%20<?php echo urlencode($inq['subject'] ?? 'Portfolio Inquiry'); ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; margin-right: 6px;" title="Reply via Email">
                    <i class="fa-solid fa-reply"></i>
                    <span>Reply</span>
                  </a>
                  <form method="POST" action="inquiries.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($inq['id'] ?? ''); ?>">
                    <button type="submit" class="btn-delete" title="Delete Inquiry">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

  </div>

  <script>
    // Live Search Filter
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('inquiriesTable');
    if (searchInput && table) {
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(query) ? '' : 'none';
        });
      });
    }

    // Theme Toggle
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeIcon = document.getElementById('themeIcon');
    const savedTheme = localStorage.getItem('loki_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeBtn) {
      themeBtn.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme') || 'dark';
        const next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('loki_theme', next);
        updateThemeIcon(next);
      });
    }

    function updateThemeIcon(t) {
      if (themeIcon) {
        themeIcon.className = t === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
      }
    }
  </script>
</body>
</html>

