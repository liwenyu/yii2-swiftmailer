#!/bin/bash

# Microsoft Exchange 配置快速验证脚本

echo "🚀 Microsoft Exchange 配置验证工具"
echo "====================================="
echo ""

# 检查 PHP 是否安装
if ! command -v php &> /dev/null; then
    echo "❌ PHP 未安装或不在 PATH 中"
    exit 1
fi

# 检查配置文件是否存在
if [ ! -f "test-config.php" ]; then
    echo "📝 创建配置文件..."
    cp test-config.php.template test-config.php
    echo "✅ 已创建 test-config.php 文件"
    echo ""
    echo "请编辑 test-config.php 文件，填入您的真实配置信息："
    echo "  - clientId: Azure 应用客户端ID"
    echo "  - clientSecret: Azure 应用客户端密钥"
    echo "  - tenantId: Azure 租户ID"
    echo "  - userEmail: 发送邮件的用户邮箱"
    echo ""
    echo "编辑完成后，再次运行此脚本进行验证。"
    exit 0
fi

# 检查 Composer 依赖是否安装
if [ ! -d "../vendor" ]; then
    echo "📦 安装 Composer 依赖..."
    cd .. && composer install --no-dev --optimize-autoloader && cd Microsoft_check
    echo ""
fi

# 运行验证
echo "🔍 开始验证配置..."
echo ""
php verify-config.php

# 检查验证结果
if [ $? -eq 0 ]; then
    echo ""
    echo "🎉 验证完成！如果所有项目都显示 ✅，说明配置正确。"
    echo "现在您可以在 Yii2 应用中使用此扩展发送邮件了。"
else
    echo ""
    echo "❌ 验证过程中遇到错误，请检查配置信息。"
    echo "详细说明请查看 Microsoft_check/VALIDATION_GUIDE.md 文件。"
fi
