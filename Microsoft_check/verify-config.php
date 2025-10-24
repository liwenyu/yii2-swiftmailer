<?php

/**
 * Microsoft Exchange 配置验证工具
 * 
 * 这个工具可以帮助您验证 Microsoft Exchange 配置是否正确
 * 使用方法：php verify-config.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use liwenyu\swiftmailer\Mailer;
use liwenyu\swiftmailer\MicrosoftMailConfig;
use liwenyu\swiftmailer\GraphApiService;

class ConfigVerifier
{
    private $config;
    private $mailer;
    private $graphService;

    public function __construct($configData)
    {
        $this->config = new MicrosoftMailConfig($configData);
        $this->mailer = new Mailer(['config' => $this->config]);
        $this->graphService = new GraphApiService(['config' => $this->config]);
    }

    /**
     * 验证配置
     */
    public function verify()
    {
        echo "🔍 开始验证 Microsoft Exchange 配置...\n\n";

        // 1. 验证基本配置
        $this->verifyBasicConfig();

        // 2. 验证认证
        $this->verifyAuthentication();

        // 3. 验证 API 连接
        $this->verifyApiConnection();

        // 4. 验证邮件发送权限
        $this->verifyMailPermissions();

        // 5. 测试邮件发送
        $this->testMailSending();

        echo "\n✅ 配置验证完成！\n";
    }

    /**
     * 验证基本配置
     */
    private function verifyBasicConfig()
    {
        echo "1️⃣ 验证基本配置...\n";
        
        $requiredFields = ['clientId', 'clientSecret', 'tenantId', 'userEmail'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($this->config->$field)) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            echo "❌ 缺少必需配置字段: " . implode(', ', $missingFields) . "\n";
            return false;
        }

        echo "✅ 基本配置完整\n";
        echo "   - 客户端ID: " . substr($this->config->clientId, 0, 8) . "...\n";
        echo "   - 租户ID: " . substr($this->config->tenantId, 0, 8) . "...\n";
        echo "   - 用户邮箱: " . $this->config->userEmail . "\n";
        echo "   - Graph API URL: " . $this->config->graphApiUrl . "\n";
        echo "   - 认证URL: " . $this->config->authUrl . "\n\n";

        return true;
    }

    /**
     * 验证认证
     */
    private function verifyAuthentication()
    {
        echo "2️⃣ 验证 OAuth2 认证...\n";
        
        try {
            $accessToken = $this->config->getAccessToken();
            if ($accessToken) {
                echo "✅ OAuth2 认证成功\n";
                echo "   - 访问令牌: " . substr($accessToken, 0, 20) . "...\n";
                echo "   - 令牌长度: " . strlen($accessToken) . " 字符\n\n";
                return true;
            } else {
                echo "❌ 无法获取访问令牌\n\n";
                return false;
            }
        } catch (\Exception $e) {
            echo "❌ 认证失败: " . $e->getMessage() . "\n\n";
            return false;
        }
    }

    /**
     * 验证 API 连接
     */
    private function verifyApiConnection()
    {
        echo "3️⃣ 验证 Microsoft Graph API 连接...\n";
        
        try {
            if ($this->graphService->testConnection()) {
                echo "✅ Graph API 连接成功\n";
                
                // 获取用户信息
                $userInfo = $this->graphService->getUserInfo();
                if ($userInfo) {
                    echo "   - 用户名称: " . ($userInfo['displayName'] ?? 'N/A') . "\n";
                    echo "   - 用户邮箱: " . ($userInfo['mail'] ?? 'N/A') . "\n";
                    echo "   - 用户ID: " . ($userInfo['id'] ?? 'N/A') . "\n";
                }
                echo "\n";
                return true;
            } else {
                echo "❌ Graph API 连接失败\n\n";
                return false;
            }
        } catch (\Exception $e) {
            echo "❌ API 连接错误: " . $e->getMessage() . "\n\n";
            return false;
        }
    }

    /**
     * 验证邮件发送权限
     */
    private function verifyMailPermissions()
    {
        echo "4️⃣ 验证邮件发送权限...\n";
        
        try {
            // 尝试获取邮件文件夹来验证权限
            $folders = $this->graphService->getMailFolders();
            if ($folders && isset($folders['value'])) {
                echo "✅ 邮件权限验证成功\n";
                echo "   - 可访问的邮件文件夹数量: " . count($folders['value']) . "\n";
                
                foreach ($folders['value'] as $folder) {
                    echo "   - 文件夹: " . $folder['displayName'] . " (" . $folder['totalItemCount'] . " 封邮件)\n";
                }
                echo "\n";
                return true;
            } else {
                echo "❌ 无法访问邮件文件夹\n\n";
                return false;
            }
        } catch (\Exception $e) {
            echo "❌ 权限验证失败: " . $e->getMessage() . "\n\n";
            return false;
        }
    }

    /**
     * 测试邮件发送
     */
    private function testMailSending()
    {
        echo "5️⃣ 测试邮件发送...\n";
        
        try {
            // 发送测试邮件到自己的邮箱
            $testSubject = '配置验证测试邮件 - ' . date('Y-m-d H:i:s');
            $testBody = '<h1>配置验证测试</h1><p>这是一封自动发送的测试邮件，用于验证 Microsoft Exchange 配置是否正确。</p><p>发送时间: ' . date('Y-m-d H:i:s') . '</p>';
            
            $result = $this->mailer->sendSimpleMail(
                $this->config->userEmail, // 发送给自己
                $testSubject,
                $testBody,
                'text/html'
            );
            
            if ($result) {
                echo "✅ 测试邮件发送成功\n";
                echo "   - 收件人: " . $this->config->userEmail . "\n";
                echo "   - 主题: " . $testSubject . "\n";
                echo "   - 请检查您的邮箱收件箱\n\n";
                return true;
            } else {
                echo "❌ 测试邮件发送失败\n\n";
                return false;
            }
        } catch (\Exception $e) {
            echo "❌ 邮件发送错误: " . $e->getMessage() . "\n\n";
            return false;
        }
    }
}

// 主程序
function main()
{
    echo "🚀 Microsoft Exchange 配置验证工具\n";
    echo "=====================================\n\n";

    // 检查是否提供了配置文件
    $configFile = __DIR__ . '/test-config.php';
    if (!file_exists($configFile)) {
        echo "❌ 配置文件不存在: {$configFile}\n";
        echo "请创建 test-config.php 文件，内容如下：\n\n";
        echo "<?php\n";
        echo "return [\n";
        echo "    'clientId' => 'your-client-id',\n";
        echo "    'clientSecret' => 'your-client-secret',\n";
        echo "    'tenantId' => 'your-tenant-id',\n";
        echo "    'userEmail' => 'your-email@yourdomain.com',\n";
        echo "];\n";
        exit(1);
    }

    // 加载配置
    $configData = require $configFile;
    
    try {
        $verifier = new ConfigVerifier($configData);
        $verifier->verify();
    } catch (\Exception $e) {
        echo "❌ 验证过程中发生错误: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// 运行主程序
if (php_sapi_name() === 'cli') {
    main();
} else {
    echo "此工具只能在命令行环境下运行。\n";
    echo "使用方法: php verify-config.php\n";
}
