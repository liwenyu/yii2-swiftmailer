# 快速开始指南

## 1. 安装扩展

```bash
composer require liwenyu/yii2-swiftmailer
```

## 2. Azure 应用注册

1. 访问 [Azure 门户](https://portal.azure.com/)
2. 创建新的应用注册
3. 记录以下信息：
   - 应用程序（客户端）ID
   - 目录（租户）ID
   - 客户端密钥
4. 添加 API 权限：
   - `Mail.Send`
   - `Mail.ReadWrite`
   - `User.Read`

## 3. 配置应用

在 `config/web.php` 中添加：

```php
'components' => [
    'microsoftMail' => [
        'class' => 'liwenyu\swiftmailer\Mailer',
        'config' => [
            'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
            'clientId' => 'your-client-id',
            'clientSecret' => 'your-client-secret',
            'tenantId' => 'your-tenant-id',
            'userEmail' => 'your-email@yourdomain.com',
        ],
    ],
],
```

## 4. 发送第一封邮件

```php
use Yii;

// 简单邮件
$result = Yii::$app->microsoftMail->sendSimpleMail(
    'recipient@example.com',
    '测试邮件',
    '<h1>Hello World!</h1>',
    'text/html'
);

if ($result) {
    echo "邮件发送成功！";
}
```

## 5. 运行测试

```bash
composer test
```

## 6. 查看完整文档

请查看 [README.md](README.md) 获取详细的使用说明和示例。
