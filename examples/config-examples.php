<?php

/**
 * Microsoft Mail Extension 配置示例
 * 
 * 这个文件展示了如何在 Yii2 应用中配置 Microsoft Mail 扩展
 */

return [
    // 配置示例 1: 使用 Microsoft Graph API（默认模式）
    'components' => [
        'microsoftMail' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'config' => [
                'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
                'clientId' => 'your-client-id',                    // Azure 应用客户端ID
                'clientSecret' => 'your-client-secret',            // Azure 应用客户端密钥
                'tenantId' => 'your-tenant-id',                    // Azure 租户ID
                'userEmail' => 'your-email@yourdomain.com',       // 发送邮件的用户邮箱
            ],
            'debug' => YII_DEBUG,                                  // 启用调试模式
        ],
    ],
    
    // 配置示例 2: 使用 SMTP 传输
    'components' => [
        'microsoftMail' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'useSmtp' => true,                                     // 启用 SMTP 模式
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',                        // SMTP 服务器
                'username' => 'your-email@gmail.com',              // SMTP 用户名
                'password' => 'your-password',                     // SMTP 密码
                'port' => '587',                                   // SMTP 端口
                'encryption' => 'tls',                             // 加密方式
            ],
            'debug' => YII_DEBUG,                                  // 启用调试模式
        ],
    ],
];

// 高级配置示例
/*
return [
    'components' => [
        'microsoftMail' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'config' => [
                'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
                'clientId' => 'your-client-id',
                'clientSecret' => 'your-client-secret',
                'tenantId' => 'your-tenant-id',
                'userEmail' => 'your-email@yourdomain.com',
                'graphApiUrl' => 'https://graph.microsoft.com/v1.0',  // 可选，自定义 Graph API URL
                'authUrl' => 'https://login.microsoftonline.com',     // 可选，自定义认证 URL
            ],
            'messageClass' => 'liwenyu\swiftmailer\Message', // 可选，自定义消息类
            'debug' => true,                                          // 启用调试模式
        ],
    ],
];
*/

// 使用环境变量的配置示例
/*
return [
    'components' => [
        'microsoftMail' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'config' => [
                'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
                'clientId' => $_ENV['MICROSOFT_CLIENT_ID'] ?? 'default-client-id',
                'clientSecret' => $_ENV['MICROSOFT_CLIENT_SECRET'] ?? 'default-client-secret',
                'tenantId' => $_ENV['MICROSOFT_TENANT_ID'] ?? 'default-tenant-id',
                'userEmail' => $_ENV['MICROSOFT_USER_EMAIL'] ?? 'default@example.com',
            ],
            'debug' => $_ENV['APP_DEBUG'] === 'true',
        ],
    ],
];
*/

// 多环境配置示例
/*
// config/web.php (生产环境)
return [
    'components' => [
        'microsoftMail' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'config' => [
                'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
                'clientId' => 'production-client-id',
                'clientSecret' => 'production-client-secret',
                'tenantId' => 'production-tenant-id',
                'userEmail' => 'noreply@yourdomain.com',
            ],
            'debug' => false,
        ],
    ],
];

// config/web-dev.php (开发环境)
return [
    'components' => [
        'microsoftMail' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'config' => [
                'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
                'clientId' => 'dev-client-id',
                'clientSecret' => 'dev-client-secret',
                'tenantId' => 'dev-tenant-id',
                'userEmail' => 'dev@yourdomain.com',
            ],
            'debug' => true,
        ],
    ],
];
*/
