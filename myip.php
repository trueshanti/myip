<?php

// Get client's IP address
$client_ipv4 = $_SERVER['REMOTE_ADDR'];

// Get client's forwarded-for IP address (if set)
$client_ipv6 = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;

// If client is not connected via IPv6, set $client_ipv6 to null
if (filter_var($client_ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    $client_ipv6 = null;
}

// Get client's hostname
$hostname = gethostbyaddr($client_ipv4);

// Get data for client's IP address
$ip_data = file_get_contents('http://ip-api.com/json/' . $client_ipv4);
$ip_data = json_decode($ip_data);
$ipv4 = $ip_data->{'query'};
$ipv6 = $ip_data->{'ip'};
$city = $ip_data->{'city'};
$region = $ip_data->{'region'};
$country = $ip_data->{'country'};
$isp = $ip_data->{'isp'};
$as = $ip_data->{'as'};
$lat = $ip_data->{'lat'};
$lon = $ip_data->{'lon'};
$asn = explode(' ', $as, 2);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My IP Address</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        :root {
            /* Dark Reader-ish variables */
            --bg: #0b1115; /* page background */
            --surface: #0f1418; /* surface / container */
            --muted: #9aa4ad; /* muted text */
            --text: #e6edf3; /* main text color */
            --accent: #58a6ff; /* link color */
            --border: rgba(255,255,255,0.06); /* subtle border */
            --row: rgba(255,255,255,0.02); /* row striping */
        }
        /* Compact body + table layout for IP info - dark mode */
        body {
            /* Use a consistent system monospace stack for a fixed-width look */
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Courier New", monospace;
            /* Disable ligatures so IPs and AS numbers show strictly */
            font-variant-ligatures: none;
            font-size: 13px;
            line-height: 1.15;
            color: var(--text);
            background: var(--bg);
            margin: 0;
            padding: 0.5rem;
        }
        .container { max-width: 900px; margin: 0 auto; padding: 0; background: transparent; }
        /* Ensure text across the page uses monospace */
        .container, table, th, td, h1, footer, a { font-family: inherit; font-variant-ligatures: none; }

        /* Table styling: dark surface, compact, readable */
        table.info { width: 100%; border-collapse: collapse; margin: 0; background: rgba(255,255,255,0.01); }
        table.info th { text-align: left; padding: 6px 10px; vertical-align: top; font-weight: 600; white-space: nowrap; min-width: 8ch; color: var(--text); }
        table.info td { padding: 6px 10px; vertical-align: top; color: var(--text); }
        table.info tr + tr th, table.info tr + tr td { border-top: 1px solid var(--border); }
        table.info tr:nth-child(odd) td { background: transparent; }
        table.info tr:nth-child(even) td { background: var(--row); }

        /* Heading & footer */
        h1 { margin: 0 0 6px 0; font-size: 1.05rem; color: var(--text); }
        footer { margin-top: 8px; font-size: 12px; color: var(--muted); }
        footer p { margin: 0; }

        a { color: var(--accent); }
        a:visited { color: #8ab4ff; }
        a:hover { text-decoration: underline; }

        /* Responsive stack for narrow screens */
        @media (max-width: 520px) {
            table.info th, table.info td { display: block; width: auto; }
            table.info th { font-weight: 700; margin-top: 6px; }
        }

        /* Respect user's preferred color-scheme if they have light mode preference */
        @media (prefers-color-scheme: light) {
            :root {
                --bg: #fff;
                --surface: #fff;
                --muted: #555;
                --text: #222;
                --accent: #0645ad;
                --border: rgba(0,0,0,0.06);
                --row: rgba(0,0,0,0.02);
            }
        }
    </style>


</head>

<body>
<!--
    <header>
        <h1>Ip.dousse.eu</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="traceroute.php">Traceroute</a>
            <a href="atlas/">Probe</a>
	</nav>
	<div class="menu-button"></div>
    </header>
-->

    <div class="container">
        <h1>Your Public IP Address</h1>
        <table class="info" role="table" aria-label="IP information">
            <tbody>
                <tr>
                    <th scope="row">IPv4</th>
                    <td><?php echo htmlspecialchars($client_ipv4); ?></td>
                </tr>
                <?php if (!empty($client_ipv6)): ?>
                <tr>
                    <th scope="row">IPv6</th>
                    <td><?php echo htmlspecialchars($client_ipv6); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th scope="row">Hostname</th>
                    <td><?php echo htmlspecialchars($hostname ?: 'Unknown'); ?></td>
                </tr>
                <tr>
                    <th scope="row">City</th>
                    <td><?php echo htmlspecialchars($city ?: 'Unknown'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Region</th>
                    <td><?php echo htmlspecialchars($region ?: 'Unknown'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Country</th>
                    <td><?php echo htmlspecialchars($country ?: 'Unknown'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Service Provider (ISP)</th>
                    <td><?php echo htmlspecialchars($isp ?: 'Unknown'); ?></td>
                </tr>
                <tr>
                    <th scope="row">AS Number</th>
                    <td>
                        <?php if (!empty($asn[0])): ?>
                            <a href="https://bgp.tools/<?php echo htmlspecialchars($asn[0]); ?>" target="_blank"><?php echo htmlspecialchars($as ?: 'Unknown'); ?></a>
                        <?php else: ?>
                            <?php echo htmlspecialchars($as ?: 'Unknown'); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <footer>
     <p>Copyright 2025 | <a href="https://github.com/trueshanti/">shanti</a></p>
    </footer>

<script>
        // Toggle the "burger menu" open and closed (guard if header is removed)
        var mb = document.querySelector(".menu-button");
        if (mb) {
            mb.addEventListener("click", function () {
            this.classList.toggle("open");
            document.querySelector("nav").classList.toggle("open");
            });
        }
    </script>
</body>

</html>
