# Run this script as Administrator
param (
    [string]$Domain
)

# Self-elevation check
if (-not ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    $arguments = "& '" + $myinvocation.mycommand.definition + "'"
    if ($Domain) { $arguments += " -Domain $Domain" }
    Start-Process powershell -Verb RunAs -ArgumentList $arguments
    exit
}

if (-not $Domain) {
    Write-Host "Please provide a domain name. Usage: .\add-domain.ps1 mytenant.webappbacninh.test" -ForegroundColor Red
    exit
}

$hostsPath = "$env:windir\System32\drivers\etc\hosts"
$ip = "127.0.0.1"
$entry = "$ip       $Domain"

if (-not (Test-Path $hostsPath)) {
    Write-Host "Hosts file not found at $hostsPath" -ForegroundColor Red
    exit
}

$content = Get-Content $hostsPath
if ($content -match [regex]::Escape($Domain)) {
    Write-Host "Domain $Domain already exists in hosts file." -ForegroundColor Yellow
}
else {
    try {
        Add-Content -Path $hostsPath -Value $entry -ErrorAction Stop
        Write-Host "Successfully added $Domain to hosts file." -ForegroundColor Green
        # Refresh DNS cache
        ipconfig /flushdns
    }
    catch {
        Write-Host "Failed to write to hosts file. Please run PowerShell as Administrator." -ForegroundColor Red
        Write-Host "Error: $_"
    }
}
