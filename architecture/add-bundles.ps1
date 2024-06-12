# Windows Powershell Script

$CURRENT_FOLDER=(Get-Location)
Set-Location (Split-Path $MyInvocation.Line -Parent)
Set-Location ../

$MAIN_FOLDER=".\website\src\Spipu"
$BASE_GIT="git@github.com:spipu/symfony-bundle"

function setBundle($code, $folder)
{
    Write-Output "Bundle [$code]"

    if (!(Test-Path $MAIN_FOLDER\$folder)) {
        Write-Output "  => Git Clone"
        git clone $BASE_GIT-$code.git $MAIN_FOLDER/$folder
    } else {
        Write-Output "  => Git Pull"
        $before=(Get-Location)
        Set-Location $MAIN_FOLDER/$folder
        git pull
        git fetch
        Set-Location $before
    }
}

if (!(Test-Path $MAIN_FOLDER)) {
    New-Item -ItemType directory -Path $MAIN_FOLDER
}

setBundle "configuration" "ConfigurationBundle"
setBundle "core"          "CoreBundle"
setBundle "process"       "ProcessBundle"
setBundle "ui"            "UiBundle"
setBundle "user"          "UserBundle"
setBundle "dashboard"     "DashboardBundle"
setBundle "api-partner"   "ApiPartnerBundle"

Set-Location $CURRENT_FOLDER
