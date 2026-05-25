<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Complaint Received</title>
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
        .badge-new {
            background: #c4ff0e;
            color: #1a1a2e;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .info-section {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #c4ff0e;
        }
        .info-label {
            font-weight: bold;
            color: #1a1a2e;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 16px;
        }
        .message-box {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-style: italic;
        }
        .actions {
            margin: 25px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #c4ff0e;
            color: #1a1a2e;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 0 10px;
        }
        .btn-secondary {
            background: #1a1a2e;
            color: #c4ff0e;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
            margin-top: 20px;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .stat-box {
            background: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            flex: 1;
            margin: 0 5px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #c4ff0e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📬 New Customer Complaint</h1>
        <div class="badge-new">URGENT - NEEDS ATTENTION</div>
    </div>
    
    <div class="content">
        <p><strong>Hello Admin,</strong></p>
        
        <p>A new complaint has been submitted through the website. Please review and respond as soon as possible.</p>
        
        <div class="info-section">
            <div class="info-label">📋 Complaint ID</div>
            <div class="info-value">#{{ $complaint->id }}</div>
        </div>
        
        <div class="info-section">
            <div class="info-label">👤 Customer Information</div>
            <div class="info-value">
                <strong>Name:</strong> {{ $complaint->name }}<br>
                <strong>Email:</strong> <a href="mailto:{{ $complaint->email }}">{{ $complaint->email }}</a><br>
                <strong>Submitted:</strong> {{ $complaint->created_at->format('F j, Y g:i A') }} ({{ $complaint->created_at->diffForHumans() }})
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">🏷️ Topic / Category</div>
            <div class="info-value">
                <strong>{{ $complaint->topic_label }}</strong>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">💬 Message Content</div>
            <div class="message-box">
                {{ $complaint->message }}
            </div>
        </div>
        
        <div class="stats">
            <div class="stat-box">
                <div class="stat-number">{{ \App\Models\Complaint::where('is_read', false)->count() }}</div>
                <div>Unread Complaints</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">{{ \App\Models\Complaint::whereDate('created_at', today())->count() }}</div>
                <div>Today's Complaints</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">{{ \App\Models\Complaint::count() }}</div>
                <div>Total Complaints</div>
            </div>
        </div>
        
        {{-- <div class="actions">
            <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn">📖 View Complaint</a>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">📋 View All Complaints</a>
        </div>
         --}}
        <div class="info-section">
            <div class="info-label">⚡ Quick Actions</div>
            <div class="info-value">
                • Reply to customer: <a href="mailto:{{ $complaint->email }}?subject=Re: {{ $complaint->topic_label }} - Complaint #{{ $complaint->id }}">Click here to reply</a><br>
                • Mark as resolved: Login to admin panel
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from your complaint management system.</p>
        <p>Please ensure you respond to customers within 2-6 hours as promised.</p>
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</body>
</html>