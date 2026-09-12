$ErrorActionPreference = 'Stop'

$exportRoot = $PSScriptRoot
$staticRoot = Split-Path $exportRoot -Parent
$themeRoot = Join-Path $exportRoot 'njc-global-editable'
$utf8 = [System.Text.UTF8Encoding]::new($false)

$pages = @(
  @{ Source = 'index.html'; Target = 'front-page.php'; Script = 'front-page.js' },
  @{ Source = 'view/about-us.html'; Target = 'about-us.php'; Script = 'about-us.js' },
  @{ Source = 'view/projects.html'; Target = 'projects.php'; Script = 'projects.js' },
  @{ Source = 'view/contact-us.html'; Target = 'contact-us.php'; Script = 'contact-us.js' },
  @{ Source = 'view/privacy.html'; Target = 'privacy.php'; Script = 'privacy.js' },
  @{ Source = 'view/terms.html'; Target = 'terms.php'; Script = 'terms.js' }
)

foreach ($page in $pages) {
  $sourcePath = Join-Path $staticRoot $page.Source
  $html = [System.IO.File]::ReadAllText($sourcePath)
  $mainMatch = [regex]::Match($html, '<main\b[^>]*>.*?</main>', [System.Text.RegularExpressions.RegexOptions]::Singleline)

  if (-not $mainMatch.Success) {
    throw "No main element found in $sourcePath"
  }

  $markup = $mainMatch.Value

  if ($page.Target -eq 'contact-us.php') {
    $contactOpen = @'
<form id="contact-form" data-njc-server-form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
              <input type="hidden" name="action" value="njc_inquiry">
              <?php wp_nonce_field( 'njc_inquiry', 'njc_inquiry_nonce' ); ?>
              <label class="njc-form-trap" aria-hidden="true">Website<input name="company_website" type="text" tabindex="-1" autocomplete="off"></label>
'@
    $subscribeOpen = @'
<form id="community-form" data-njc-server-form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
            <input type="hidden" name="action" value="njc_subscribe">
            <?php wp_nonce_field( 'njc_subscribe', 'njc_subscribe_nonce' ); ?>
            <label class="njc-form-trap" aria-hidden="true">Website<input name="company_website" type="text" tabindex="-1" autocomplete="off"></label>
'@
    $markup = $markup.Replace('<form id="contact-form">', $contactOpen.TrimEnd())
    $markup = $markup.Replace('name="inquiry-type"', 'name="inquiry_type"')
    $markup = $markup.Replace('<form id="community-form">', $subscribeOpen.TrimEnd())
    $markup = $markup.Replace('<input id="community-contact-email" type="email"', '<input id="community-contact-email" name="email" type="email"')
  }

  if ($page.Target -eq 'projects.php') {
    $projectOpen = @'
<form class="project-inquiry" data-njc-server-form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
              <input type="hidden" name="action" value="njc_inquiry">
              <?php wp_nonce_field( 'njc_inquiry', 'njc_inquiry_nonce' ); ?>
              <label class="njc-form-trap" aria-hidden="true">Website<input name="company_website" type="text" tabindex="-1" autocomplete="off"></label>
'@
    $markup = $markup.Replace('<form class="project-inquiry">', $projectOpen.TrimEnd())
    $markup = $markup.Replace('name="interest"', 'name="inquiry_type"')
  }

  $targetPath = Join-Path $themeRoot ('template-parts/legacy/' + $page.Target)
  [System.IO.File]::WriteAllText($targetPath, $markup + [Environment]::NewLine, $utf8)

  $scriptMatches = [regex]::Matches($html, '<script(?![^>]*\bsrc=)[^>]*>(.*?)</script>', [System.Text.RegularExpressions.RegexOptions]::Singleline)
  $scripts = @($scriptMatches | ForEach-Object { $_.Groups[1].Value.Trim() } | Where-Object { $_ })
  $scriptPath = Join-Path $themeRoot ('assets/js/pages/' + $page.Script)
  [System.IO.File]::WriteAllText($scriptPath, ($scripts -join ([Environment]::NewLine + [Environment]::NewLine)) + [Environment]::NewLine, $utf8)
}

Copy-Item -LiteralPath (Join-Path $staticRoot 'assets/css/style.css') -Destination (Join-Path $themeRoot 'assets/css/site.css') -Force
Copy-Item -LiteralPath (Join-Path $staticRoot 'assets/js/site.js') -Destination (Join-Path $themeRoot 'assets/js/site.js') -Force
Copy-Item -LiteralPath (Join-Path $staticRoot 'assets/images') -Destination (Join-Path $themeRoot 'assets') -Recurse -Force

Write-Output 'Static reference synchronized into the WordPress theme copy.'
