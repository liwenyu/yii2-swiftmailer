<?php

namespace liwenyu\swiftmailer;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Microsoft Mail Configuration Component
 * 
 * 处理微软邮件服务的认证和配置
 * 
 * @author liwenyu <liwenyu66@gmail.com>
 * @since 1.0.0
 */
class MicrosoftMailConfig extends Component
{
    /**
     * @var string 客户端ID
     */
    public $clientId;
    
    /**
     * @var string 客户端密钥
     */
    public $clientSecret;
    
    /**
     * @var string 租户ID
     */
    public $tenantId;
    
    /**
     * @var string 发送邮件的用户邮箱
     */
    public $userEmail;
    
    /**
     * @var string Microsoft Graph API 基础URL
     */
    public $graphApiUrl = 'https://graph.microsoft.com/v1.0';
    
    /**
     * @var string OAuth2 认证URL
     */
    public $authUrl = 'https://login.microsoftonline.com';
    
    /**
     * @var string 访问令牌
     */
    private $_accessToken;
    
    /**
     * @var int 令牌过期时间
     */
    private $_tokenExpiresAt;
    
    /**
     * @var Client HTTP客户端
     */
    private $_httpClient;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (empty($this->clientId)) {
            throw new InvalidConfigException('clientId 不能为空');
        }
        
        if (empty($this->clientSecret)) {
            throw new InvalidConfigException('clientSecret 不能为空');
        }
        
        if (empty($this->tenantId)) {
            throw new InvalidConfigException('tenantId 不能为空');
        }
        
        if (empty($this->userEmail)) {
            throw new InvalidConfigException('userEmail 不能为空');
        }
        
        $this->_httpClient = new Client([
            'timeout' => 30,
            'verify' => true,
        ]);
    }

    /**
     * 获取访问令牌
     * 
     * @return string
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function getAccessToken()
    {
        // 如果令牌仍然有效，直接返回
        if ($this->_accessToken && $this->_tokenExpiresAt && time() < $this->_tokenExpiresAt - 60) {
            return $this->_accessToken;
        }
        
        return $this->refreshAccessToken();
    }

    /**
     * 刷新访问令牌
     * 
     * @return string
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function refreshAccessToken()
    {
        $tokenUrl = $this->authUrl . '/' . $this->tenantId . '/oauth2/v2.0/token';
        
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ];
        
        try {
            $response = $this->_httpClient->post($tokenUrl, [
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            if (!isset($data['access_token'])) {
                throw new InvalidConfigException('获取访问令牌失败: ' . ($data['error_description'] ?? '未知错误'));
            }
            
            $this->_accessToken = $data['access_token'];
            $this->_tokenExpiresAt = time() + ($data['expires_in'] ?? 3600);
            
            return $this->_accessToken;
            
        } catch (GuzzleException $e) {
            throw new InvalidConfigException('获取访问令牌时发生错误: ' . $e->getMessage());
        }
    }

    /**
     * 获取HTTP客户端
     * 
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->_httpClient;
    }

    /**
     * 获取Graph API完整URL
     * 
     * @param string $endpoint
     * @return string
     */
    public function getGraphApiUrl($endpoint = '')
    {
        return rtrim($this->graphApiUrl, '/') . '/' . ltrim($endpoint, '/');
    }

    /**
     * 验证配置
     * 
     * @return bool
     */
    public function validateConfig()
    {
        try {
            $this->getAccessToken();
            return true;
        } catch (\Exception $e) {
            Yii::error('Microsoft Mail 配置验证失败: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
