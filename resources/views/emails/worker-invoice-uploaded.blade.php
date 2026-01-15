<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Worker Invoice Uploaded</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #DBB88E;">Worker Invoice Uploaded</h2>
        
        <p>Hello,</p>
        
        <p>A new invoice has been uploaded by a worker. Here are the details:</p>
        
        <div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Worker Name:</strong> {{ $worker->name }}</p>
            <p><strong>Email:</strong> {{ $worker->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $worker->phone ?? 'N/A' }}</p>
            <p><strong>Invoice File:</strong> {{ basename($invoice->file_path) }}</p>
        </div>
        
        <p>Please log in to the admin panel to review and process this invoice.</p>
        
        <p style="margin-top: 30px;">
            <a href="{{ config('app.url') }}/admin" 
               style="background-color: #DBB88E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View in Admin Panel
            </a>
        </p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
        
        <p style="font-size: 12px; color: #999;">
            This is an automated notification from CuroHelp.
        </p>
    </div>
</body>
</html>
