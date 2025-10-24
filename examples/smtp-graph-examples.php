<?php

/**
 * SMTP 和 Microsoft Graph API 使用示例
 * 
 * 展示如何使用两种不同的传输方式
 */

use Yii;
use liwenyu\swiftmailer\Mailer;
use liwenyu\swiftmailer\MicrosoftMailConfig;

// 示例 1: 使用 Microsoft Graph API 发送邮件
function example1_graphApiMail()
{
    echo "=== 示例 1: Microsoft Graph API 邮件发送 ===\n";
    
    $config = new MicrosoftMailConfig([
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'tenantId' => 'your-tenant-id',
        'userEmail' => 'your-email@yourdomain.com',
    ]);
    
    $mailer = new Mailer([
        'config' => $config,
        'debug' => true,
    ]);
    
    // 发送邮件
    $result = $mailer->sendSimpleMail(
        'recipient@example.com',
        'Graph API 邮件',
        '<h1>通过 Microsoft Graph API 发送的邮件</h1>',
        'text/html'
    );
    
    if ($result) {
        echo "Graph API 邮件发送成功！\n";
    } else {
        echo "Graph API 邮件发送失败！\n";
    }
}

// 示例 2: 使用 SMTP 发送邮件
function example2_smtpMail()
{
    echo "\n=== 示例 2: SMTP 邮件发送 ===\n";
    
    $mailer = new Mailer([
        'useSmtp' => true, // 启用 SMTP 模式
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'your-email@gmail.com',
            'password' => 'your-password',
            'port' => '587',
            'encryption' => 'tls',
        ],
        'debug' => true,
    ]);
    
    // 发送邮件
    $result = $mailer->sendSimpleMail(
        'recipient@example.com',
        'SMTP 邮件',
        '<h1>通过 SMTP 发送的邮件</h1>',
        'text/html'
    );
    
    if ($result) {
        echo "SMTP 邮件发送成功！\n";
    } else {
        echo "SMTP 邮件发送失败！\n";
    }
}

// 示例 3: 动态切换传输方式
function example3_dynamicSwitch()
{
    echo "\n=== 示例 3: 动态切换传输方式 ===\n";
    
    // 根据环境变量决定使用哪种传输方式
    $useSmtp = $_ENV['USE_SMTP'] ?? false;
    
    if ($useSmtp) {
        echo "使用 SMTP 传输...\n";
        $mailer = new Mailer([
            'useSmtp' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'your-email@gmail.com',
                'password' => 'your-password',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ]);
    } else {
        echo "使用 Microsoft Graph API...\n";
        $config = new MicrosoftMailConfig([
            'clientId' => 'your-client-id',
            'clientSecret' => 'your-client-secret',
            'tenantId' => 'your-tenant-id',
            'userEmail' => 'your-email@yourdomain.com',
        ]);
        
        $mailer = new Mailer([
            'config' => $config,
        ]);
    }
    
    // 获取发送器信息
    $info = $mailer->getMailerInfo();
    echo "发送器信息: " . json_encode($info, JSON_UNESCAPED_UNICODE) . "\n";
    
    // 检查是否可用
    if ($mailer->isAvailable()) {
        echo "邮件发送器可用！\n";
        
        // 发送邮件
        $result = $mailer->sendSimpleMail(
            'recipient@example.com',
            '动态切换邮件',
            '<h1>通过动态选择的传输方式发送的邮件</h1>',
            'text/html'
        );
        
        if ($result) {
            echo "邮件发送成功！\n";
        } else {
            echo "邮件发送失败！\n";
        }
    } else {
        echo "邮件发送器不可用！\n";
    }
}

// 示例 4: 在 Yii2 应用中使用
function example4_yii2Integration()
{
    echo "\n=== 示例 4: Yii2 应用集成 ===\n";
    
    // 假设这是在 Yii2 应用的控制器中
    // 配置已经在 config/web.php 中完成
    
    // 发送欢迎邮件（使用配置的传输方式）
    $message = Yii::$app->microsoftMail->compose('welcome-email', [
        'userName' => '张三',
        'activationLink' => 'https://example.com/activate?token=123',
    ])
        ->setTo('user@example.com')
        ->setSubject('欢迎注册！');
    
    $result = Yii::$app->microsoftMail->send($message);
    
    if ($result) {
        echo "欢迎邮件发送成功！\n";
    } else {
        echo "欢迎邮件发送失败！\n";
    }
    
    // 获取发送器信息
    $info = Yii::$app->microsoftMail->getMailerInfo();
    echo "当前使用的传输方式: " . $info['transport'] . "\n";
}

// 运行所有示例
if (php_sapi_name() === 'cli') {
    echo "SMTP 和 Microsoft Graph API 使用示例\n";
    echo "====================================\n\n";
    
    // 注意：在实际运行前，请确保已经正确配置了相应的认证信息
    echo "注意：在实际运行前，请确保已经正确配置了相应的认证信息\n\n";
    
    // example1_graphApiMail();
    // example2_smtpMail();
    // example3_dynamicSwitch();
    // example4_yii2Integration();
    
    echo "示例代码已准备就绪，请取消注释相应的函数调用来运行示例。\n";
} else {
    echo "此文件只能在命令行环境下运行。\n";
}
