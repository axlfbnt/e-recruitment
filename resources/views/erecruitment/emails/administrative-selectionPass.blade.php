<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification of Successful Administrative Selection</title>
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
            background-color: #17a497;
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

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #17a497;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
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
            <h1>Notification of Passed Administrative Selection</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ Auth::user()->name }}</strong>,</p>

            <p>We are pleased to inform you that you have successfully passed the Administrative Selection for the
                position of [Job Position] at PT United Tractors Pandu Engineering.</p>

            <p>As part of the next phase of our recruitment process, you are invited to participate in the
                <strong>Psychological Test</strong>. Details regarding the schedule and format of the test will be
                provided to you shortly. Please stay tuned for further updates.
            </p>

            <p>If you have any further questions or require additional information, please feel free to contact us using
                the details provided below. We are happy to assist you with any inquiries.</p>

            <p>Thank you for your interest in joining our team. We look forward to your continued
                participation in the recruitment process.</p>

            <a href="[NextStepLink]" class="button">View Psychological Test Details</a>
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
