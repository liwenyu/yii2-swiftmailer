# Microsoft Exchange 配置验证指南

## 🔧 **验证步骤**

### 1. 准备配置文件

1. 复制配置模板：

```bash
cp test-config.php.template test-config.php
```

2. 编辑 `test-config.php` 文件，填入您的真实配置：

```php
<?php
return [
    'clientId' => 'your-actual-client-id',
    'clientSecret' => 'your-actual-client-secret',
    'tenantId' => 'your-actual-tenant-id',
    'userEmail' => 'your-actual-email@yourdomain.com',
];
```

### 2. 运行验证工具

```bash
php verify-config.php
```

### 3. 查看验证结果

验证工具会检查以下项目：

- ✅ **基本配置** - 检查必需字段是否填写
- ✅ **OAuth2 认证** - 验证客户端凭据是否正确
- ✅ **API 连接** - 测试与 Microsoft Graph API 的连接
- ✅ **邮件权限** - 验证是否有邮件发送权限
- ✅ **邮件发送** - 发送测试邮件验证功能

## 📋 **Azure 应用注册检查清单**

### 必需权限

确保您的 Azure 应用具有以下权限：

- `Mail.Send` - 发送邮件
- `Mail.ReadWrite` - 读写邮件
- `User.Read` - 读取用户信息

### 权限类型

- **应用程序权限** (Application permissions) - 用于客户端凭据流程
- **管理员同意** - 确保权限已被管理员同意

## 🔍 **常见问题排查**

### 1. 认证失败

```
❌ 认证失败: AADSTS7000215: Invalid client secret is provided
```

**解决方案**：

- 检查 `clientSecret` 是否正确
- 检查客户端密钥是否已过期
- 重新生成客户端密钥

### 2. 权限不足

```
❌ 权限验证失败: Insufficient privileges to complete the operation
```

**解决方案**：

- 检查应用权限配置
- 确保管理员已同意权限
- 验证 `userEmail` 是否有发送邮件的权限

### 3. 租户 ID 错误

```
❌ 认证失败: AADSTS90002: Tenant 'xxx' not found
```

**解决方案**：

- 检查 `tenantId` 是否正确
- 使用 `common` 或 `organizations` 作为租户 ID

### 4. 用户邮箱问题

```
❌ 无法访问邮件文件夹
```

**解决方案**：

- 检查 `userEmail` 是否存在
- 确保邮箱已激活
- 验证用户是否有 Exchange Online 许可证

## 🛠️ **手动验证步骤**

如果自动验证失败，可以手动验证：

### 1. 验证 OAuth2 令牌

```bash
curl -X POST "https://login.microsoftonline.com/{tenant-id}/oauth2/v2.0/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "client_id={client-id}&client_secret={client-secret}&scope=https://graph.microsoft.com/.default&grant_type=client_credentials"
```

### 2. 验证 Graph API 访问

```bash
curl -X GET "https://graph.microsoft.com/v1.0/users/{user-email}" \
  -H "Authorization: Bearer {access-token}"
```

### 3. 验证邮件发送权限

```bash
curl -X GET "https://graph.microsoft.com/v1.0/users/{user-email}/mailFolders" \
  -H "Authorization: Bearer {access-token}"
```

## 📊 **验证结果说明**

### 成功示例

```
🔍 开始验证 Microsoft Exchange 配置...

1️⃣ 验证基本配置...
✅ 基本配置完整
   - 客户端ID: 12345678...
   - 租户ID: 87654321...
   - 用户邮箱: user@company.com
   - Graph API URL: https://graph.microsoft.com/v1.0
   - 认证URL: https://login.microsoftonline.com

2️⃣ 验证 OAuth2 认证...
✅ OAuth2 认证成功
   - 访问令牌: eyJ0eXAiOiJKV1QiLCJub...
   - 令牌长度: 1234 字符

3️⃣ 验证 Microsoft Graph API 连接...
✅ Graph API 连接成功
   - 用户名称: John Doe
   - 用户邮箱: user@company.com
   - 用户ID: 12345678-1234-1234-1234-123456789012

4️⃣ 验证邮件发送权限...
✅ 邮件权限验证成功
   - 可访问的邮件文件夹数量: 5
   - 文件夹: 收件箱 (0 封邮件)
   - 文件夹: 已发送邮件 (0 封邮件)
   - 文件夹: 已删除邮件 (0 封邮件)
   - 文件夹: 草稿 (0 封邮件)
   - 文件夹: 垃圾邮件 (0 封邮件)

5️⃣ 测试邮件发送...
✅ 测试邮件发送成功
   - 收件人: user@company.com
   - 主题: 配置验证测试邮件 - 2024-01-15 10:30:00
   - 请检查您的邮箱收件箱

✅ 配置验证完成！
```

### 失败示例

```
🔍 开始验证 Microsoft Exchange 配置...

1️⃣ 验证基本配置...
❌ 缺少必需配置字段: clientSecret, tenantId

2️⃣ 验证 OAuth2 认证...
❌ 认证失败: config 必须是 MicrosoftMailConfig 的实例

3️⃣ 验证 Microsoft Graph API 连接...
❌ Graph API 连接失败

4️⃣ 验证邮件发送权限...
❌ 权限验证失败: config 必须是 MicrosoftMailConfig 的实例

5️⃣ 测试邮件发送...
❌ 邮件发送错误: config 必须是 MicrosoftMailConfig 的实例

✅ 配置验证完成！
```

## 🔒 **安全注意事项**

1. **不要提交真实配置** - 将 `test-config.php` 添加到 `.gitignore`
2. **定期轮换密钥** - 定期更新客户端密钥
3. **最小权限原则** - 只授予必要的权限
4. **监控使用情况** - 定期检查 API 使用情况

## 📞 **获取帮助**

如果验证过程中遇到问题，请检查：

1. Azure 门户中的应用注册配置
2. 权限是否已正确授予和同意
3. 用户邮箱是否有效且已激活
4. 网络连接是否正常

验证成功后，您就可以在 Yii2 应用中使用此扩展发送邮件了！
