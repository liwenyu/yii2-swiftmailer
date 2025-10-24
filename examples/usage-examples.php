<?php

/**
 * Microsoft Mail Extension 使用示例
 * 
 * 这个文件展示了如何使用 Yii2 Microsoft Mail 扩展的各种功能
 */

use Yii;
use liwenyu\swiftmailer\Mailer;
use liwenyu\swiftmailer\MicrosoftMailConfig;
use liwenyu\swiftmailer\GraphApiService;

// 示例 1: 基本配置和简单邮件发送
function example1_basicMailSending()
{
    echo "=== 示例 1: 基本邮件发送 ===\n";
    
    // 配置 Microsoft Mail
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
    
    // 发送简单邮件
    $result = $mailer->sendSimpleMail(
        'recipient@example.com',
        '测试邮件主题',
        '<h1>Hello World!</h1><p>这是一封测试邮件。</p>',
        'text/html'
    );
    
    if ($result) {
        echo "邮件发送成功！\n";
    } else {
        echo "邮件发送失败！\n";
    }
}

// 示例 2: 使用消息对象发送复杂邮件
function example2_complexMail()
{
    echo "\n=== 示例 2: 复杂邮件发送 ===\n";
    
    $config = new MicrosoftMailConfig([
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'tenantId' => 'your-tenant-id',
        'userEmail' => 'your-email@yourdomain.com',
    ]);
    
    $mailer = new Mailer([
        'config' => $config,
    ]);
    
    // 创建消息对象
    $message = $mailer->compose()
        ->setFrom('sender@yourdomain.com')
        ->setTo('recipient@example.com')
        ->setCc('cc@example.com')
        ->setBcc('bcc@example.com')
        ->setSubject('复杂邮件示例')
        ->setHtmlBody('<h1>HTML 内容</h1><p>这是HTML格式的邮件内容。</p>')
        ->setTextBody('纯文本内容：这是纯文本格式的邮件内容。')
        ->attachFile('/path/to/document.pdf')
        ->attach('custom.txt', '这是自定义附件内容', 'text/plain');
    
    $result = $mailer->send($message);
    
    if ($result) {
        echo "复杂邮件发送成功！\n";
    } else {
        echo "复杂邮件发送失败！\n";
    }
}

// 示例 3: 批量发送邮件
function example3_bulkMail()
{
    echo "\n=== 示例 3: 批量邮件发送 ===\n";
    
    $config = new MicrosoftMailConfig([
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'tenantId' => 'your-tenant-id',
        'userEmail' => 'your-email@yourdomain.com',
    ]);
    
    $mailer = new Mailer([
        'config' => $config,
    ]);
    
    $recipients = [
        'user1@example.com',
        'user2@example.com',
        'user3@example.com',
    ];
    
    $results = $mailer->sendBulkMail(
        $recipients,
        '批量邮件通知',
        '<p>这是批量发送的邮件内容。</p><p>感谢您的关注！</p>',
        'text/html'
    );
    
    foreach ($results as $email => $success) {
        echo "{$email}: " . ($success ? '成功' : '失败') . "\n";
    }
}

// 示例 4: 使用 Graph API 服务
function example4_graphApiService()
{
    echo "\n=== 示例 4: Graph API 服务 ===\n";
    
    $config = new MicrosoftMailConfig([
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'tenantId' => 'your-tenant-id',
        'userEmail' => 'your-email@yourdomain.com',
    ]);
    
    $graphService = new GraphApiService([
        'config' => $config,
    ]);
    
    // 测试连接
    if ($graphService->testConnection()) {
        echo "API 连接正常！\n";
        
        // 获取用户信息
        $userInfo = $graphService->getUserInfo();
        if ($userInfo) {
            echo "用户: " . $userInfo['displayName'] . "\n";
            echo "邮箱: " . $userInfo['mail'] . "\n";
        }
        
        // 获取邮件列表
        $messages = $graphService->getMessages(null, null, 3);
        if ($messages && isset($messages['value'])) {
            echo "最近的邮件:\n";
            foreach ($messages['value'] as $message) {
                echo "- 主题: " . $message['subject'] . "\n";
                echo "  发件人: " . $message['from']['emailAddress']['address'] . "\n";
                echo "  时间: " . $message['receivedDateTime'] . "\n\n";
            }
        }
        
        // 获取邮件文件夹
        $folders = $graphService->getMailFolders();
        if ($folders && isset($folders['value'])) {
            echo "邮件文件夹:\n";
            foreach ($folders['value'] as $folder) {
                echo "- " . $folder['displayName'] . " (" . $folder['totalItemCount'] . " 封邮件)\n";
            }
        }
    } else {
        echo "API 连接失败！\n";
    }
}

// 示例 5: 邮件草稿功能
function example5_draftMail()
{
    echo "\n=== 示例 5: 邮件草稿功能 ===\n";
    
    $config = new MicrosoftMailConfig([
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'tenantId' => 'your-tenant-id',
        'userEmail' => 'your-email@yourdomain.com',
    ]);
    
    $graphService = new GraphApiService([
        'config' => $config,
    ]);
    
    // 创建草稿
    $draftData = [
        'subject' => '草稿邮件示例',
        'body' => [
            'contentType' => 'HTML',
            'content' => '<h1>草稿邮件</h1><p>这是一封草稿邮件，您可以稍后发送。</p>',
        ],
        'toRecipients' => [
            [
                'emailAddress' => [
                    'address' => 'recipient@example.com',
                    'name' => '收件人',
                ],
            ],
        ],
    ];
    
    $draft = $graphService->createDraft($draftData);
    if ($draft) {
        echo "草稿创建成功，ID: " . $draft['id'] . "\n";
        
        // 发送草稿
        $result = $graphService->sendDraft($draft['id']);
        if ($result) {
            echo "草稿发送成功！\n";
        } else {
            echo "草稿发送失败！\n";
        }
    } else {
        echo "草稿创建失败！\n";
    }
}

// 示例 6: 错误处理和配置验证
function example6_errorHandling()
{
    echo "\n=== 示例 6: 错误处理 ===\n";
    
    try {
        // 验证配置
        $config = new MicrosoftMailConfig([
            'clientId' => 'your-client-id',
            'clientSecret' => 'your-client-secret',
            'tenantId' => 'your-tenant-id',
            'userEmail' => 'your-email@yourdomain.com',
        ]);
        
        if (!$config->validateConfig()) {
            throw new \Exception('Microsoft Mail 配置验证失败');
        }
        
        echo "配置验证成功！\n";
        
        $mailer = new Mailer([
            'config' => $config,
        ]);
        
        // 发送测试邮件
        $result = $mailer->sendSimpleMail(
            'test@example.com',
            '配置测试邮件',
            '这是一封配置测试邮件。'
        );
        
        if ($result) {
            echo "测试邮件发送成功！\n";
        } else {
            echo "测试邮件发送失败！\n";
        }
        
    } catch (\Exception $e) {
        echo "错误: " . $e->getMessage() . "\n";
    }
}

// 示例 7: 在 Yii2 应用中使用
function example7_yii2Integration()
{
    echo "\n=== 示例 7: Yii2 应用集成 ===\n";
    
    // 假设这是在 Yii2 应用的控制器或服务中
    // 配置已经在 config/web.php 中完成
    
    // 发送欢迎邮件
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
    
    // 发送系统通知
    $notificationResult = Yii::$app->microsoftMail->sendSimpleMail(
        'admin@example.com',
        '系统通知',
        '<p>系统运行正常，所有服务都在线。</p>',
        'text/html'
    );
    
    if ($notificationResult) {
        echo "系统通知发送成功！\n";
    } else {
        echo "系统通知发送失败！\n";
    }
}

// 运行所有示例
if (php_sapi_name() === 'cli') {
    echo "Microsoft Mail Extension 使用示例\n";
    echo "================================\n\n";
    
    // 注意：在实际运行前，请确保已经正确配置了 Azure 应用信息
    echo "注意：在实际运行前，请确保已经正确配置了 Azure 应用信息\n\n";
    
    // example1_basicMailSending();
    // example2_complexMail();
    // example3_bulkMail();
    // example4_graphApiService();
    // example5_draftMail();
    // example6_errorHandling();
    // example7_yii2Integration();
    
    echo "示例代码已准备就绪，请取消注释相应的函数调用来运行示例。\n";
} else {
    echo "此文件只能在命令行环境下运行。\n";
}
