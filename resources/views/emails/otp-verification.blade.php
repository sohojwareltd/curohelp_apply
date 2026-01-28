<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="margin: 0; padding: 20px; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px;">
        
        <h1 style="margin: 0 0 20px; font-size: 24px; color: #333; text-align: center;">Email Verification</h1>
        
        <p style="margin: 0 0 20px; color: #666; line-height: 1.6;">
            Please use this code to verify your email address:
        </p>
        
        <div style="background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 6px; padding: 20px; text-align: center; margin: 30px 0;">
            <div style="font-size: 32px; font-weight: bold; color: #333; letter-spacing: 6px; font-family: monospace;">
                {{ $otp }}
            </div>
        </div>
        
        <p style="margin: 0 0 15px; color: #999; font-size: 14px; text-align: center;">
            Valid for 10 minutes
        </p>
        
        <p style="margin: 20px 0 0; color: #999; font-size: 13px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
            &copy; {{ date('Y') }} CuroHelp
        </p>
        
    </div>
</body>
</html>
