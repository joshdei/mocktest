<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            color: #c4ff0e;
            margin: 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #c4ff0e;
        }
        .topic-badge {
            display: inline-block;
            padding: 5px 12px;
            background: #c4ff0e;
            color: #1a1a2e;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
        button {
            background: #c4ff0e;
            color: #1a1a2e;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✓ Message Received</h1>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $complaint->name }}</strong>,</p>
        
        <p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p>
        
        <div class="message-box">
            <p><strong>Topic:</strong> <span class="topic-badge">{{ $complaint->topic_label }}</span></p>
            <p><strong>Your Message:</strong></p>
            <p>{{ $complaint->message }}</p>
        </div>
        
        <p>We typically reply within <strong>2-6 hours</strong> during business hours. A copy of your message has been stored in our system for reference.</p>
        
        <p>If you need immediate assistance, please reply to this email or call our support line.</p>
        
        <p>Best regards,<br>
        <strong>Support Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated confirmation. Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</body>
</html>