$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

# 1. GET /
Write-Host "--- Test 1: GET / ---"
$res1 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/" -MaximumRedirection 0 -ErrorAction SilentlyContinue -WebSession $session
Write-Host "Status Code: " $res1.StatusCode
if ($res1.StatusCode -eq 302 -or $res1.StatusCode -eq 301) {
    Write-Host "Redirect Location: " $res1.Headers.Location
}

# 2. GET /login
Write-Host "`n--- Test 2: GET /login ---"
$res2 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/login" -WebSession $session
Write-Host "Status Code: " $res2.StatusCode
if ($res2.Content -match "Omega-QMS") {
    Write-Host "Content verification: 'Omega-QMS' heading found."
} else {
    Write-Host "Content verification: Heading NOT found."
}

# Extract CSRF token
$csrf = ""
if ($res2.Content -match 'name="_token" value="([^"]+)"') {
    $csrf = $Matches[1]
}

# 3. POST /login with Admin
Write-Host "`n--- Test 3: POST /login (Admin) ---"
$adminBody = @{
    "_token" = $csrf
    "email" = "admin@proofofcontent.test"
    "password" = "password"
}
$res3 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/login" -Method POST -Body $adminBody -WebSession $session -MaximumRedirection 0 -ErrorAction SilentlyContinue
Write-Host "Status Code: " $res3.StatusCode
if ($res3.StatusCode -eq 302) {
    Write-Host "Redirect Location: " $res3.Headers.Location
}

# 4. Clear session and POST /login with User
Write-Host "`n--- Test 4: POST /login (User) ---"
$sessionUser = New-Object Microsoft.PowerShell.Commands.WebRequestSession
$resGet = Invoke-WebRequest -Uri "http://127.0.0.1:8000/login" -WebSession $sessionUser
$csrfUser = ""
if ($resGet.Content -match 'name="_token" value="([^"]+)"') {
    $csrfUser = $Matches[1]
}
$userBody = @{
    "_token" = $csrfUser
    "email" = "user@proofofcontent.test"
    "password" = "password"
}
$res4 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/login" -Method POST -Body $userBody -WebSession $sessionUser -MaximumRedirection 0 -ErrorAction SilentlyContinue
Write-Host "Status Code: " $res4.StatusCode
if ($res4.StatusCode -eq 302) {
    Write-Host "Redirect Location: " $res4.Headers.Location
}

# 5. Access /admin/dashboard as user
Write-Host "`n--- Test 5: GET /admin/dashboard (as User) ---"
$res5 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/admin/dashboard" -WebSession $sessionUser -MaximumRedirection 0 -ErrorAction SilentlyContinue
Write-Host "Status Code: " $res5.StatusCode

