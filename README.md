# myip

## Purpose

This small PHP project shows the client's public IP address and related geolocation metadata (city, region, country), ISP, ASN, and hostname. It determines the client IP from `$_SERVER['REMOTE_ADDR']` and `HTTP_X_FORWARDED_FOR` (if set) and queries `ip-api.com` for lookup data.

demo: https://mojo.cc/myip

<img width="1145" height="323" alt="image" src="https://github.com/user-attachments/assets/01f7158f-a945-466e-8c4c-3ddb09498458" />

## Usage

- Deploy `myip.php` on a PHP-capable web server (Apache, Nginx + PHP-FPM) and open it in a browser.
- The page displays the visitor IP, hostname, and location info pulled from the external API.

## Privacy & Security

- This repo does not store visitor IPs; it only displays the IP of the current request.

