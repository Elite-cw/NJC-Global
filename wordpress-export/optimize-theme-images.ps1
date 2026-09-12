$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.Drawing

$themeRoot = Join-Path $PSScriptRoot 'njc-global-editable'
$imageRoot = Join-Path $themeRoot 'assets/images'

function Resize-ThemeImage {
  param(
    [Parameter(Mandatory)] [string] $RelativePath,
    [Parameter(Mandatory)] [int] $MaxWidth,
    [Parameter(Mandatory)] [int] $MaxHeight,
    [int] $JpegQuality = 86
  )

  $path = Join-Path $imageRoot $RelativePath
  $source = [System.Drawing.Image]::FromFile($path)

  try {
    $scale = [Math]::Min($MaxWidth / $source.Width, $MaxHeight / $source.Height)
    if ($scale -ge 1) {
      return
    }

    $width = [Math]::Max(1, [int] [Math]::Round($source.Width * $scale))
    $height = [Math]::Max(1, [int] [Math]::Round($source.Height * $scale))
    $bitmap = [System.Drawing.Bitmap]::new($width, $height)

    try {
      $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
      try {
        $graphics.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality
        $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
        $graphics.DrawImage($source, 0, 0, $width, $height)
      } finally {
        $graphics.Dispose()
      }

      $extension = [System.IO.Path]::GetExtension($path).ToLowerInvariant()
      $temporaryPath = $path + '.optimized' + $extension

      if ($extension -in @('.jpg', '.jpeg')) {
        $jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object MimeType -eq 'image/jpeg'
        $parameters = [System.Drawing.Imaging.EncoderParameters]::new(1)
        $parameters.Param[0] = [System.Drawing.Imaging.EncoderParameter]::new([System.Drawing.Imaging.Encoder]::Quality, [long] $JpegQuality)
        $bitmap.Save($temporaryPath, $jpegCodec, $parameters)
        $parameters.Dispose()
      } else {
        $bitmap.Save($temporaryPath, [System.Drawing.Imaging.ImageFormat]::Png)
      }
    } finally {
      $bitmap.Dispose()
    }
  } finally {
    $source.Dispose()
  }

  Move-Item -LiteralPath $temporaryPath -Destination $path -Force
}

function Convert-ThemePngToJpeg {
  param(
    [Parameter(Mandatory)] [string] $RelativePath,
    [int] $JpegQuality = 86
  )

  $sourcePath = Join-Path $imageRoot $RelativePath
  $targetPath = [System.IO.Path]::ChangeExtension($sourcePath, '.jpg')
  $source = [System.Drawing.Image]::FromFile($sourcePath)

  try {
    $bitmap = [System.Drawing.Bitmap]::new($source.Width, $source.Height)
    try {
      $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
      try {
        $graphics.Clear([System.Drawing.Color]::White)
        $graphics.DrawImage($source, 0, 0, $source.Width, $source.Height)
      } finally {
        $graphics.Dispose()
      }

      $jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object MimeType -eq 'image/jpeg'
      $parameters = [System.Drawing.Imaging.EncoderParameters]::new(1)
      $parameters.Param[0] = [System.Drawing.Imaging.EncoderParameter]::new([System.Drawing.Imaging.Encoder]::Quality, [long] $JpegQuality)
      $bitmap.Save($targetPath, $jpegCodec, $parameters)
      $parameters.Dispose()
    } finally {
      $bitmap.Dispose()
    }
  } finally {
    $source.Dispose()
  }

  Remove-Item -LiteralPath $sourcePath
}

Resize-ThemeImage 'team/joycepic1.jpg' 1300 1800
Resize-ThemeImage 'team/daniel-friday.png' 900 900
Resize-ThemeImage 'team/daniel-nwezeh.png' 900 900
Resize-ThemeImage 'team/abongwa-celestin.png' 900 1125
Resize-ThemeImage 'about-hero-triptych.png' 1600 700
Resize-ThemeImage 'njc-global.png' 700 700
Resize-ThemeImage 'njc-global_(no-bg).png' 700 700
Resize-ThemeImage 'partners/africa-for-africa-women.png' 600 600
Resize-ThemeImage 'partners/renewberry.png' 700 400

Convert-ThemePngToJpeg 'team/daniel-friday.png'
Convert-ThemePngToJpeg 'team/daniel-nwezeh.png'
Convert-ThemePngToJpeg 'team/abongwa-celestin.png'

$siteScriptPath = Join-Path $themeRoot 'assets/js/site.js'
$siteScript = [System.IO.File]::ReadAllText($siteScriptPath)
$siteScript = $siteScript.Replace('team/daniel-friday.png', 'team/daniel-friday.jpg')
$siteScript = $siteScript.Replace('team/daniel-nwezeh.png', 'team/daniel-nwezeh.jpg')
$siteScript = $siteScript.Replace('team/abongwa-celestin.png', 'team/abongwa-celestin.jpg')
[System.IO.File]::WriteAllText($siteScriptPath, $siteScript, [System.Text.UTF8Encoding]::new($false))

foreach ($unused in @('team/tunde-adebayo.png', 'team/zainab-bello.png', 'team/chioma-okafor.png')) {
  $unusedPath = Join-Path $imageRoot $unused
  if (Test-Path -LiteralPath $unusedPath) {
    Remove-Item -LiteralPath $unusedPath
  }
}

Write-Output 'WordPress-copy images optimized.'
