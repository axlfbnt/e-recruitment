<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification of Unsuccessful Administrative Selection</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #f7473a;
            color: white;
            text-align: center;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            text-align: justify;
        }

        .content p {
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            margin-top: 20px;
        }

        .footer p {
            margin: 0;
        }

        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
        }

        .signature p {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Notification of Rejected Administrative Selection</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ Auth::user()->name }}</strong>,</p>

            <p>We regret to inform you that, after careful consideration, you were not selected to advance to the next
                stage of the recruitment process for the position of [Job Position] at PT United Tractors Pandu
                Engineering.</p>

            <p>While this decision was difficult, we encourage you to apply for other opportunities that may arise in
                the
                future. We appreciate your interest in our company and your effort throughout the application process.
            </p>

            <p>If you have any further questions or require additional information, please feel free to contact us using
                the details provided below. We are happy to assist you with any inquiries.</p>

            <p>Thank you for your application, and we wish you the best of luck in your future career
                endeavors.</p>
        </div>

        <div class="signature">
            <p>Best Regards,</p>
            <p><strong>Admin Recruitment Patria</strong><br>
                PT United Tractors Pandu Engineering<br>
                Jl. Jababeka XI Blok H 30 - 40 Cikarang - Bekasi 17530<br>
                Phone: +62 811 8589243 | Email: <a href="mailto:hrd@patria.co.id">hrd@patria.co.id</a></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} PT United Tractors Pandu Engineering. All rights reserved.</p>
        </div>
    </div>
</body>

</html>