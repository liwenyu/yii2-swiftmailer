# Yii2 Mailer Extension

基于 yii2-swiftmailer 的 Yii2 扩展，实现微软邮件发送功能。

## 功能特性

- ✅ 基于 Microsoft Graph API 的邮件发送
- ✅ 支持 Office 365 和 Outlook 邮件服务
- ✅ 支持 HTML 和纯文本邮件
- ✅ 支持邮件附件
- ✅ 支持抄送和密送
- ✅ 支持批量发送
- ✅ 支持邮件草稿功能
- ✅ 支持邮件管理（读取、删除、标记已读）
- ✅ 完整的错误处理和日志记录
- ✅ **完全兼容官方 yii2-swiftmailer 的所有功能**

## 与官方 yii2-swiftmailer 的功能对比

### ✅ **完全支持的功能**

#### 1. 基础邮件发送功能

- ✅ **继承 BaseMailer** - 完全兼容 Yii2 邮件发送器接口
- ✅ **compose() 方法** - 创建邮件消息
- ✅ **send() 方法** - 发送邮件
- ✅ **视图模板支持** - 支持 Yii2 视图模板渲染
- ✅ **布局支持** - 支持邮件布局模板

#### 2. 消息属性支持

- ✅ **主题设置** - `setSubject()`, `getSubject()`
- ✅ **收件人设置** - `setTo()`, `getTo()`
- ✅ **抄送设置** - `setCc()`, `getCc()`
- ✅ **密送设置** - `setBcc()`, `getBcc()`
- ✅ **发件人设置** - `setFrom()`, `getFrom()`
- ✅ **回复地址** - `setReplyTo()`, `getReplyTo()`
- ✅ **字符编码** - `setCharset()`, `getCharset()`
- ✅ **HTML 内容** - `setHtmlBody()`
- ✅ **纯文本内容** - `setTextBody()`
- ✅ **内容获取** - `toString()`

#### 3. 附件功能

- ✅ **文件附件** - `attachFile($filePath, $options)`
- ✅ **内容附件** - `attach($fileName, $options)`
- ✅ **内容附件** - `attachContent($content, $options)`
- ✅ **嵌入图片** - `embed($filePath, $cid)`
- ✅ **嵌入内容** - `embedContent($content, $contentType, $cid)`
- ✅ **获取嵌入 CID** - `getEmbeddedCid($filePath)`
- ✅ **附件列表** - `getAttachments()`
- ✅ **自动内容类型检测** - 支持常见文件类型

#### 4. 邮件属性

- ✅ **优先级设置** - `setPriority()`, `getPriority()`
- ✅ **重要性设置** - `setImportance()`, `getImportance()`
- ✅ **敏感度设置** - `setSensitivity()`, `getSensitivity()`

### 🚀 **额外增强功能**

#### 1. Microsoft Graph API 集成

- ✅ **OAuth2 认证** - 自动获取和管理访问令牌
- ✅ **邮件管理** - 读取、删除、标记已读邮件
- ✅ **用户信息** - 获取用户详细信息
- ✅ **邮件文件夹** - 管理邮件文件夹
- ✅ **草稿功能** - 创建和发送邮件草稿

#### 2. 高级功能

- ✅ **批量发送** - `sendBulkMail()`
- ✅ **简单发送** - `sendSimpleMail()`
- ✅ **发送器信息** - `getMailerInfo()`
- ✅ **可用性检查** - `isAvailable()`
- ✅ **发送统计** - `getStats()`
- ✅ **调试模式** - 详细的日志记录
- ✅ **错误处理** - 完善的异常处理机制
- ✅ **配置验证** - 自动验证配置有效性

### 📋 **功能对比表**

| 功能                | 官方 yii2-swiftmailer | 本扩展 | 说明     |
| ------------------- | --------------------- | ------ | -------- |
| **基础发送**        | ✅                    | ✅     | 完全兼容 |
| **视图模板**        | ✅                    | ✅     | 完全兼容 |
| **HTML/文本**       | ✅                    | ✅     | 完全兼容 |
| **附件**            | ✅                    | ✅     | 完全兼容 |
| **嵌入图片**        | ✅                    | ✅     | 完全兼容 |
| **抄送/密送**       | ✅                    | ✅     | 完全兼容 |
| **回复地址**        | ✅                    | ✅     | 完全兼容 |
| **字符编码**        | ✅                    | ✅     | 完全兼容 |
| **优先级**          | ✅                    | ✅     | 完全兼容 |
| **重要性**          | ✅                    | ✅     | 完全兼容 |
| **敏感度**          | ✅                    | ✅     | 完全兼容 |
| **批量发送**        | ❌                    | ✅     | 扩展功能 |
| **简单发送**        | ❌                    | ✅     | 扩展功能 |
| **Microsoft Graph** | ❌                    | ✅     | 扩展功能 |
| **邮件管理**        | ❌                    | ✅     | 扩展功能 |
| **OAuth2 认证**     | ❌                    | ✅     | 扩展功能 |

### 🔄 **无缝迁移**

您可以直接替换官方 yii2-swiftmailer，现有代码无需任何修改：

```php
// 原来的代码完全兼容
$message = Yii::$app->mailer->compose()
    ->setTo('recipient@example.com')
    ->setSubject('测试邮件')
    ->setHtmlBody('<h1>Hello World!</h1>')
    ->attachFile('/path/to/file.pdf');

Yii::$app->mailer->send($message);
```

## 🔄 **从官方 yii2-swiftmailer 迁移**

### 1. 安装扩展

```bash
composer require liwenyu/yii2-swiftmailer
```

### 2. 更新配置

#### 使用 Microsoft Graph API（推荐）

```php
// 新的配置 - Microsoft Graph API
'mailer' => [
    'class' => 'liwenyu\swiftmailer\Mailer',
    'config' => [
        'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
        'clientId' => 'your-client-id',
        'clientSecret' => 'your-client-secret',
        'tenantId' => 'your-tenant-id',
        'userEmail' => 'your-email@yourdomain.com',
    ],
],
```

#### 继续使用 SMTP 传输

```php
// 新的配置 - 继续使用 SMTP
'mailer' => [
    'class' => 'liwenyu\swiftmailer\Mailer',
    'useSmtp' => true, // 启用 SMTP 模式
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'your-email@gmail.com',
        'password' => 'your-password',
        'port' => '587',
        'encryption' => 'tls',
    ],
],
```

**配置差异说明**：

- **Microsoft Graph API 模式**：使用 `config` 配置 OAuth2 认证信息
- **SMTP 模式**：使用 `transport` 配置 SMTP 传输（与官方 yii2-swiftmailer 相同）
- **模式选择**：通过 `useSmtp` 参数控制使用哪种传输方式
- **向后兼容**：支持两种模式，可以无缝迁移

### 3. 代码无需修改

```php
// 原来的代码完全兼容，无需任何修改
$message = Yii::$app->mailer->compose()
    ->setTo('recipient@example.com')
    ->setSubject('测试邮件')
    ->setHtmlBody('<h1>Hello World!</h1>')
    ->attachFile('/path/to/file.pdf');

Yii::$app->mailer->send($message);
```

## 🔧 配置验证工具

为了方便验证 Microsoft Exchange 配置，我们提供了专门的验证工具：

### 📁 Microsoft_check 目录

`Microsoft_check/` 目录包含了完整的配置验证工具：

- **`verify-config.php`** - 主要的验证工具，执行详细的配置验证
- **`test-config.php.template`** - 配置模板文件
- **`VALIDATION_GUIDE.md`** - 详细的验证指南和故障排除
- **`verify.sh`** - 快速验证脚本

### 🚀 快速验证

```bash
# 进入验证工具目录
cd Microsoft_check

# 运行快速验证脚本
./verify.sh
```

### 📋 验证项目

验证工具会检查以下 5 个方面：

1. **✅ 基本配置** - 检查必需字段是否填写
2. **✅ OAuth2 认证** - 验证客户端凭据是否正确
3. **✅ API 连接** - 测试与 Microsoft Graph API 的连接
4. **✅ 邮件权限** - 验证是否有邮件发送权限
5. **✅ 邮件发送** - 发送测试邮件验证功能

### 🔒 安全特性

- ✅ **敏感信息保护** - 真实配置不会显示完整内容
- ✅ **本地验证** - 所有验证都在您的本地环境进行
- ✅ **Git 忽略** - 配置文件已添加到 .gitignore
- ✅ **详细日志** - 提供详细的验证步骤和错误信息

详细说明请查看 [`Microsoft_check/README.md`](Microsoft_check/README.md) 文件。

## 安装

### 通过 Composer 安装

```bash
composer require liwenyu/yii2-swiftmailer
```

### 手动安装

1. 下载扩展文件到 `vendor/liwenyu/yii2-swiftmailer` 目录
2. 在 `composer.json` 中添加自动加载配置

## 配置

### 1. 在应用配置中注册组件

#### 使用 Microsoft Graph API（推荐）

```php
// config/web.php 或 config/console.php
return [
    'components' => [
        'mailer' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'config' => [
                'class' => 'liwenyu\swiftmailer\MicrosoftMailConfig',
                'clientId' => 'your-client-id',
                'clientSecret' => 'your-client-secret',
                'tenantId' => 'your-tenant-id',
                'userEmail' => 'your-email@yourdomain.com',
            ],
            'debug' => YII_DEBUG, // 可选，启用调试模式
        ],
    ],
];
```

#### 使用 SMTP 传输

```php
// config/web.php 或 config/console.php
return [
    'components' => [
        'mailer' => [
            'class' => 'liwenyu\swiftmailer\Mailer',
            'useSmtp' => true, // 启用 SMTP 模式
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'your-email@gmail.com',
                'password' => 'your-password',
                'port' => '587',
                'encryption' => 'tls',
            ],
            'debug' => YII_DEBUG, // 可选，启用调试模式
        ],
    ],
];
```

### 2. Microsoft Azure 应用注册

在使用此扩展之前，您需要在 Microsoft Azure 门户中注册一个应用程序：

1. 访问 [Azure 门户](https://portal.azure.com/)
2. 导航到 "Azure Active Directory" > "应用注册"
3. 点击 "新注册"
4. 填写应用信息：
   - 名称：您的应用名称
   - 支持的账户类型：选择适当的选项
   - 重定向 URI：可选
5. 注册完成后，记录以下信息：
   - 应用程序（客户端）ID
   - 目录（租户）ID
6. 在 "证书和密码" 部分创建客户端密码
7. 在 "API 权限" 部分添加以下权限：
   - `Mail.Send` (发送邮件)
   - `Mail.ReadWrite` (读写邮件)
   - `User.Read` (读取用户信息)

## 使用方法

### 基本邮件发送

```php
use Yii;

// 发送简单邮件
$result = Yii::$app->mailer->sendSimpleMail(
    'recipient@example.com',
    '邮件主题',
    '<h1>Hello World!</h1><p>这是一封测试邮件。</p>',
    'text/html'
);

if ($result) {
    echo "邮件发送成功！";
} else {
    echo "邮件发送失败！";
}
```

### 使用消息对象发送邮件

```php
use Yii;

$message = Yii::$app->mailer->compose()
    ->setFrom('sender@yourdomain.com')
    ->setTo('recipient@example.com')
    ->setCc('cc@example.com')
    ->setBcc('bcc@example.com')
    ->setSubject('邮件主题')
    ->setHtmlBody('<h1>HTML 内容</h1>')
    ->setTextBody('纯文本内容')
    ->attachFile('/path/to/file.pdf')
    ->attach('custom.txt', '自定义内容', 'text/plain');

$result = Yii::$app->mailer->send($message);
```

### 批量发送邮件

```php
use Yii;

$recipients = [
    'user1@example.com',
    'user2@example.com',
    'user3@example.com',
];

$results = Yii::$app->mailer->sendBulkMail(
    $recipients,
    '批量邮件主题',
    '<p>这是批量发送的邮件内容。</p>',
    'text/html'
);

foreach ($results as $email => $success) {
    echo "{$email}: " . ($success ? '成功' : '失败') . "\n";
}
```

### 使用 Graph API 服务

```php
use Yii;
use liwenyu\swiftmailer\GraphApiService;

// 创建 Graph API 服务实例
$graphService = new GraphApiService([
    'config' => Yii::$app->mailer->config,
]);

// 测试连接
if ($graphService->testConnection()) {
    echo "API 连接正常！";
}

// 获取用户信息
$userInfo = $graphService->getUserInfo();
if ($userInfo) {
    echo "用户: " . $userInfo['displayName'];
}

// 获取邮件列表
$messages = $graphService->getMessages(null, null, 5);
if ($messages && isset($messages['value'])) {
    foreach ($messages['value'] as $message) {
        echo "主题: " . $message['subject'] . "\n";
        echo "发件人: " . $message['from']['emailAddress']['address'] . "\n";
    }
}

// 标记邮件为已读
$graphService->markAsRead('message-id');

// 删除邮件
$graphService->deleteMessage('message-id');
```

### 使用视图模板发送邮件

```php
use Yii;

$message = Yii::$app->mailer->compose('welcome-email', [
    'userName' => '张三',
    'activationLink' => 'https://example.com/activate?token=123',
])
    ->setTo('user@example.com')
    ->setSubject('欢迎注册！');

Yii::$app->mailer->send($message);
```

对应的视图文件 `views/mail/welcome-email.php`：

```php
<?php
use yii\helpers\Html;
?>

<h1>欢迎 <?= Html::encode($userName) ?>！</h1>

<p>感谢您注册我们的服务。请点击下面的链接激活您的账户：</p>

<p><?= Html::a('激活账户', $activationLink) ?></p>

<p>如果链接无法点击，请复制以下地址到浏览器中打开：</p>
<p><?= $activationLink ?></p>
```

## 高级功能

### 邮件草稿

```php
use Yii;

// 创建草稿
$draftData = [
    'subject' => '草稿邮件',
    'body' => [
        'contentType' => 'HTML',
        'content' => '<p>这是草稿内容</p>',
    ],
    'toRecipients' => [
        [
            'emailAddress' => [
                'address' => 'recipient@example.com',
            ],
        ],
    ],
];

$graphService = new GraphApiService([
    'config' => Yii::$app->mailer->config,
]);

$draft = $graphService->createDraft($draftData);
if ($draft) {
    echo "草稿创建成功，ID: " . $draft['id'];

    // 发送草稿
    $result = $graphService->sendDraft($draft['id']);
    if ($result) {
        echo "草稿发送成功！";
    }
}
```

### 错误处理

```php
use Yii;
use liwenyu\swiftmailer\MicrosoftMailConfig;

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

    // 发送邮件
    $result = Yii::$app->mailer->sendSimpleMail(
        'recipient@example.com',
        '测试邮件',
        '测试内容'
    );

} catch (\Exception $e) {
    Yii::error('邮件发送错误: ' . $e->getMessage());
    // 处理错误
}
```

## 配置选项

### MicrosoftMailConfig 配置选项

| 选项           | 类型   | 必需 | 默认值                              | 描述                 |
| -------------- | ------ | ---- | ----------------------------------- | -------------------- |
| `clientId`     | string | 是   | -                                   | Azure 应用客户端 ID  |
| `clientSecret` | string | 是   | -                                   | Azure 应用客户端密钥 |
| `tenantId`     | string | 是   | -                                   | Azure 租户 ID        |
| `userEmail`    | string | 是   | -                                   | 发送邮件的用户邮箱   |
| `graphApiUrl`  | string | 否   | `https://graph.microsoft.com/v1.0`  | Graph API 基础 URL   |
| `authUrl`      | string | 否   | `https://login.microsoftonline.com` | OAuth2 认证 URL      |

### Mailer 配置选项

| 选项           | 类型                | 必需 | 默认值                        | 描述                                                 |
| -------------- | ------------------- | ---- | ----------------------------- | ---------------------------------------------------- |
| `config`       | MicrosoftMailConfig | 否   | -                             | Microsoft Graph API 配置对象（useSmtp=false 时必需） |
| `transport`    | array               | 否   | -                             | SMTP 传输配置（useSmtp=true 时必需）                 |
| `useSmtp`      | bool                | 否   | false                         | 是否使用 SMTP 传输                                   |
| `messageClass` | string              | 否   | `liwenyu\swiftmailer\Message` | 消息类                                               |
| `debug`        | bool                | 否   | false                         | 是否启用调试模式                                     |

## 故障排除

### 常见问题

1. **认证失败**

   - 检查 `clientId`、`clientSecret` 和 `tenantId` 是否正确
   - 确认 Azure 应用权限配置正确
   - 检查客户端密钥是否过期

2. **邮件发送失败**

   - 确认 `userEmail` 有发送邮件的权限
   - 检查收件人邮箱地址格式是否正确
   - 查看应用日志获取详细错误信息

3. **API 调用失败**
   - 检查网络连接
   - 确认 Graph API 权限配置
   - 查看 Microsoft Graph API 状态页面

### 调试模式

启用调试模式可以查看详细的请求和响应信息：

```php
'mailer' => [
    'class' => 'liwenyu\swiftmailer\Mailer',
    'config' => [
        // ... 配置选项
    ],
    'debug' => true, // 启用调试模式
],
```

## 许可证

MIT License

## 贡献

欢迎提交 Issue 和 Pull Request！

## 更新日志

### 1.0.0

- 初始版本发布
- 支持基本的邮件发送功能
- 支持 Microsoft Graph API 集成
- 支持邮件管理功能
- **完全兼容官方 yii2-swiftmailer 的所有功能**

## 🎉 **总结**

本扩展 **完全支持** 官方 yii2-swiftmailer 的所有功能，并且提供了额外的 Microsoft Graph API 集成功能。您可以：

1. **无缝替换** - 直接替换官方扩展，代码无需修改
2. **功能增强** - 享受 Microsoft Graph API 的强大功能
3. **向后兼容** - 保持与现有代码的完全兼容性
4. **扩展能力** - 使用新增的批量发送和邮件管理功能

这使得本扩展成为官方 yii2-swiftmailer 的完美替代品，同时提供了更强大的 Microsoft 邮件服务集成能力。
