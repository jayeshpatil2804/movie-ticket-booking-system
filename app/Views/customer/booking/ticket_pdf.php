<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Movie Ticket - <?= esc($booking['movie_title']) ?></title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .ticket {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border: 2px dashed #ddd;
            padding: 10px;
            position: relative;
        }
        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #e53935;
            margin-bottom: 5px;
        }
        .ticket-number {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 10px;
            color: #666;
        }
        .movie-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .details {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .detail-item {
            width: 50%;
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .seats {
            margin: 15px 0;
            text-align: center;
        }
        .seat-badge {
            display: inline-block;
            background: #e53935;
            color: white;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            font-weight: bold;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
            padding: 10px 0;
            border-top: 1px dashed #ddd;
            border-bottom: 1px dashed #ddd;
        }
        .barcode img {
            max-width: 200px;
            height: auto;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 10px;
        }
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
        }
        .cut-here {
            text-align: center;
            margin: 5px 0;
            position: relative;
        }
        .cut-here:before,
        .cut-here:after {
            content: "";
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: repeating-linear-gradient(
                to right,
                #000 0,
                #000 5px,
                transparent 5px,
                transparent 10px
            );
        }
        .cut-here:before {
            left: 0;
        }
        .cut-here:after {
            right: 0;
        }
        .terms {
            font-size: 9px;
            color: #999;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-number">#<?= esc($booking['booking_number']) ?></div>
        
        <div class="header">
            <div class="logo">MOVIE TICKETS</div>
            <div>ADMIT ONE</div>
        </div>
        
        <div class="movie-title"><?= esc($booking['movie_title']) ?></div>
        
        <div class="details">
            <div class="detail-item">
                <div class="label">Date</div>
                <div><?= date('D, M j, Y', strtotime($booking['show_time'])) ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Time</div>
                <div><?= date('g:i A', strtotime($booking['show_time'])) ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Screen</div>
                <div><?= esc($booking['screen_name']) ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Cinema</div>
                <div><?= esc($booking['cinema_name']) ?></div>
            </div>
        </div>
        
        <div class="seats">
            <?php foreach ($bookedSeats as $seat): ?>
                <span class="seat-badge"><?= esc($seat['seat_number']) ?></span>
            <?php endforeach; ?>
        </div>
        
        <div class="cut-here">CUT HERE</div>
        
        <div class="qr-code">
            <!-- You can generate a QR code with booking details here -->
            <div style="border: 1px solid #ddd; width: 100px; height: 100px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: #999;">
                QR Code
            </div>
        </div>
        
        <div class="barcode">
            <!-- You can generate a barcode with booking number here -->
            <div style="letter-spacing: 5px; font-family: 'Libre Barcode 128', cursive; font-size: 24px;">
                *<?= $booking['booking_number'] ?>*
            </div>
        </div>
        
        <div class="terms">
            <p>Please arrive at least 30 minutes before showtime. Valid photo ID required. No refunds or exchanges. No recording devices allowed.</p>
            <p>© <?= date('Y') ?> Movie Tickets. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
