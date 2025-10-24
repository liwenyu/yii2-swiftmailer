# Microsoft Exchange 配置验证工具

这个目录包含了用于验证 Microsoft Exchange 配置的工具和脚本。

## 📁 文件说明

- **`verify-config.php`** - 主要的验证工具，执行详细的配置验证
- **`test-config.php.template`** - 配置模板文件，复制后填入真实配置
- **`VALIDATION_GUIDE.md`** - 详细的验证指南和故障排除
- **`verify.sh`** - 快速验证脚本，自动执行验证流程

## 🚀 快速开始

### 方法一：使用快速脚本（推荐）

```bash
cd Microsoft_check
./verify.sh
```

### 方法二：手动步骤

```bash
cd Microsoft_check

# 1. 复制配置模板
cp test-config.php.template test-config.php

# 2. 编辑配置文件，填入您的真实配置
nano test-config.php

# 3. 运行验证工具
php verify-config.php
```

## ⚙️ 配置要求

在 `test-config.php` 中需要配置以下信息：

```php
<?php
return [
    'clientId' => 'your-azure-client-id',           // Azure 应用客户端ID
    'clientSecret' => 'your-azure-client-secret',   // Azure 应用客户端密钥
    'tenantId' => 'your-azure-tenant-id',          // Azure 租户ID
    'userEmail' => 'your-email@yourdomain.com',     // 发送邮件的用户邮箱
];
```

## 🔒 安全注意事项

- ✅ 配置文件 `test-config.php` 已添加到 `.gitignore`，不会被提交到版本控制
- ✅ 验证过程中敏感信息会被部分隐藏
- ✅ 所有验证都在本地环境进行，不会上传任何数据

## 📊 验证项目

验证工具会检查以下 5 个方面：

1. **✅ 基本配置** - 检查必需字段是否填写
2. **✅ OAuth2 认证** - 验证客户端凭据是否正确
3. **✅ API 连接** - 测试与 Microsoft Graph API 的连接
4. **✅ 邮件权限** - 验证是否有邮件发送权限
5. **✅ 邮件发送** - 发送测试邮件验证功能

## 🛠️ Azure 应用权限要求

确保您的 Azure 应用具有以下权限：

- `Mail.Send` - 发送邮件
- `Mail.ReadWrite` - 读写邮件
- `User.Read` - 读取用户信息

## 📞 获取帮助

如果验证过程中遇到问题，请查看：

- `VALIDATION_GUIDE.md` - 详细的验证指南和故障排除
- Azure 门户中的应用注册配置
- 权限是否已正确授予和同意

验证成功后，您就可以在 Yii2 应用中使用此扩展发送邮件了！
