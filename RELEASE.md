# 发布指南

## 📦 发布到 Packagist

### 1. GitHub 仓库设置

1. 在 GitHub 上创建新仓库：`liwenyu/yii2-swiftmailer`
2. 将本地仓库推送到 GitHub：

```bash
git remote add origin https://github.com/liwenyu/yii2-swiftmailer.git
git branch -M main
git push -u origin main
```

### 2. Packagist 注册

1. 访问 [Packagist](https://packagist.org/)
2. 使用 GitHub 账号登录
3. 点击 "Submit" 按钮
4. 输入仓库 URL：`https://github.com/liwenyu/yii2-swiftmailer`
5. 点击 "Check" 验证包信息
6. 点击 "Submit" 提交

### 3. 设置自动更新

1. 在 Packagist 包页面点击 "Settings"
2. 启用 "Auto-Update" 功能
3. 添加 GitHub Webhook：
   - 在 GitHub 仓库设置中添加 Webhook
   - URL: `https://packagist.org/api/github?username=liwenyu`
   - Content type: `application/json`
   - Secret: 从 Packagist 获取

## 🏷️ 版本管理

### 创建版本标签

```bash
# 创建版本标签
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0

# 后续版本
git tag -a v1.0.1 -m "Release version 1.0.1"
git push origin v1.0.1
```

### 更新 composer.json 版本

在发布新版本前，更新 `composer.json` 中的版本号：

```json
{
    "name": "liwenyu/yii2-microsoft-mail",
    "version": "1.0.1",
    "description": "Yii2 extension for Microsoft mail sending functionality based on yii2-swiftmailer",
    ...
}
```

## 📋 发布检查清单

### 发布前检查

- [ ] 所有测试通过
- [ ] 文档完整且准确
- [ ] 示例代码可运行
- [ ] 版本号正确
- [ ] 许可证文件存在
- [ ] README.md 包含安装说明
- [ ] composer.json 配置正确

### 运行测试

```bash
# 安装依赖
composer install

# 运行测试
composer test
# 或者
./vendor/bin/phpunit
```

## 🎯 发布到 Yii2 扩展目录

### 1. 准备扩展信息

确保您的扩展符合 Yii2 扩展标准：

- ✅ 遵循 PSR-4 自动加载标准
- ✅ 包含完整的文档
- ✅ 有单元测试
- ✅ 遵循 Yii2 编码规范
- ✅ 有明确的许可证

### 2. 提交到 Yii2 扩展目录

1. 访问 [Yii2 扩展目录](https://www.yiiframework.com/extensions)
2. 点击 "Submit Extension"
3. 填写扩展信息：
   - **Name**: `yii2-microsoft-mail`
   - **Description**: `Yii2 extension for Microsoft mail sending functionality based on yii2-swiftmailer`
   - **Category**: `Mail`
   - **Tags**: `microsoft`, `mail`, `email`, `office365`, `outlook`, `graph-api`
   - **Repository URL**: `https://github.com/liwenyu/yii2-swiftmailer`
   - **Packagist URL**: `https://packagist.org/packages/liwenyu/yii2-swiftmailer`
   - **License**: `MIT`
   - **Author**: `liwenyu`
   - **Author Email**: `liwenyu66@gmail.com`

### 3. 扩展描述模板

```markdown
# Yii2 Microsoft Mail Extension

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

## 安装

```bash
composer require liwenyu/yii2-swiftmailer
```

## 快速开始

```php
// 配置
'components' => [
    'microsoftMail' => [
        'class' => 'liwenyu\Yii2Swiftmailer\Mailer',
        'config' => [
            'class' => 'liwenyu\Yii2Swiftmailer\MicrosoftMailConfig',
            'clientId' => 'your-client-id',
            'clientSecret' => 'your-client-secret',
            'tenantId' => 'your-tenant-id',
            'userEmail' => 'your-email@yourdomain.com',
        ],
    ],
],
```

```php
// 发送邮件
$result = Yii::$app->microsoftMail->sendSimpleMail(
    'recipient@example.com',
    '邮件主题',
    '<h1>Hello World!</h1>',
    'text/html'
);
```

## 文档

详细文档请查看 [GitHub README](https://github.com/liwenyu/yii2-swiftmailer/blob/main/README.md)

## 许可证

MIT License
```

## 🔄 持续集成

### GitHub Actions 配置

创建 `.github/workflows/ci.yml`：

```yaml
name: CI

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    strategy:
      matrix:
        php-version: [7.4, 8.0, 8.1, 8.2]
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ matrix.php-version }}
        extensions: mbstring, intl, curl, zip
        coverage: xdebug
    
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress --no-suggest
    
    - name: Run tests
      run: composer test
    
    - name: Generate coverage report
      run: composer coverage
```

## 📊 推广和营销

### 1. 社交媒体推广

- 在 Twitter 上发布扩展信息
- 在 Reddit 的 r/PHP 和 r/yii 社区分享
- 在 LinkedIn 上发布技术文章

### 2. 技术博客

写一篇技术博客介绍扩展的功能和使用方法：

- 扩展的背景和需求
- 技术实现细节
- 使用示例和最佳实践
- 性能优化建议

### 3. 社区参与

- 在 Yii2 官方论坛分享
- 参与 Stack Overflow 相关问题的回答
- 在 GitHub 上积极维护和回复 Issues

## 📈 维护和更新

### 定期维护

- 定期更新依赖包
- 修复 Bug 和问题
- 添加新功能
- 更新文档
- 响应社区反馈

### 版本发布策略

- **主版本 (Major)**: 重大功能更新或破坏性变更
- **次版本 (Minor)**: 新功能添加
- **修订版本 (Patch)**: Bug 修复和小改进

遵循 [语义化版本控制](https://semver.org/) 规范。
