# myip

## Purpose

This small PHP project shows the client's public IP address and related geolocation metadata (city, region, country), ISP, ASN, and hostname. It determines the client IP from `$_SERVER['REMOTE_ADDR']` and `HTTP_X_FORWARDED_FOR` (if set) and queries `ip-api.com` for lookup data.

## Usage

- Deploy `myip.php` on a PHP-capable web server (Apache, Nginx + PHP-FPM) and open it in a browser.
- The page displays the visitor IP, hostname, and location info pulled from the external API.

## Privacy & Security

- This repo does not store visitor IPs; it only displays the IP of the current request.
- Avoid committing secrets: do not commit Personal Access Tokens (PATs) or private keys to the repo.
- Prefer SSH keys or a secure credential manager instead of storing tokens in plaintext.

If you want a more secure way to deploy or use authentication for Git pushes, consider adding an SSH public key to GitHub or using Git Credential Manager (or `gh auth login`).
