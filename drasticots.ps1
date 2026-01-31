Get-ChildItem -Recurse -Filter *.vue | ForEach-Object {
  $path = $_.FullName
  $text = Get-Content -Raw $path

  # 1) parenthesized single param: const fn = (date) => ...  -> const fn = (date:any) =>
  $text = $text -replace 'const\s+([A-Za-z_$][\w$]*)\s*=\s*\(\s*([A-Za-z_$][\w$]*)\s*\)\s*=>', 'const $1 = ($2:any) =>'

  # 2) unparenthesized single param: const fn = date => ... -> const fn = (date:any) =>
  $text = $text -replace 'const\s+([A-Za-z_$][\w$]*)\s*=\s*([A-Za-z_$][\w$]*)\s*=>', 'const $1 = ($2:any) =>'

  if ($text -ne (Get-Content -Raw $path)) {
    Set-Content -LiteralPath $path -Value $text -Encoding utf8
    Write-Host "Modificado: $path"
  }
}