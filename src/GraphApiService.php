<?php

namespace liwenyu\swiftmailer;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Microsoft Graph API Service
 * 
 * 微软 Graph API 集成服务
 * 
 * @author liwenyu <liwenyu66@gmail.com>
 * @since 1.0.0
 */
class GraphApiService extends Component
{
    /**
     * @var MicrosoftMailConfig 配置对象
     */
    public $config;
    
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
        
        if (!$this->config instanceof MicrosoftMailConfig) {
            throw new InvalidConfigException('config 必须是 MicrosoftMailConfig 的实例');
        }
        
        $this->_httpClient = new Client([
            'timeout' => 30,
            'verify' => true,
        ]);
    }

    /**
     * 发送邮件
     * 
     * @param array $mailData 邮件数据
     * @return bool
     */
    public function sendMail($mailData)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $endpoint = $this->config->getGraphApiUrl("users/{$this->config->userEmail}/sendMail");
            
            $response = $this->_httpClient->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $mailData,
            ]);
            
            return $response->getStatusCode() === 202;
            
        } catch (GuzzleException $e) {
            Yii::error('Graph API 发送邮件失败: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * 获取用户信息
     * 
     * @param string $userId 用户ID（可选，默认使用配置中的用户邮箱）
     * @return array|null
     */
    public function getUserInfo($userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}");
            
            $response = $this->_httpClient->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            }
            
            return null;
            
        } catch (GuzzleException $e) {
            Yii::error('获取用户信息失败: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    /**
     * 获取邮件文件夹
     * 
     * @param string $userId 用户ID（可选）
     * @return array|null
     */
    public function getMailFolders($userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/mailFolders");
            
            $response = $this->_httpClient->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            }
            
            return null;
            
        } catch (GuzzleException $e) {
            Yii::error('获取邮件文件夹失败: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    /**
     * 获取邮件列表
     * 
     * @param string $userId 用户ID（可选）
     * @param string $folderId 文件夹ID（可选，默认为收件箱）
     * @param int $top 返回数量限制
     * @return array|null
     */
    public function getMessages($userId = null, $folderId = null, $top = 10)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/messages");
            if ($folderId) {
                $endpoint = $this->config->getGraphApiUrl("users/{$userId}/mailFolders/{$folderId}/messages");
            }
            
            $endpoint .= '?$top=' . $top;
            
            $response = $this->_httpClient->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            }
            
            return null;
            
        } catch (GuzzleException $e) {
            Yii::error('获取邮件列表失败: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    /**
     * 获取特定邮件
     * 
     * @param string $messageId 邮件ID
     * @param string $userId 用户ID（可选）
     * @return array|null
     */
    public function getMessage($messageId, $userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/messages/{$messageId}");
            
            $response = $this->_httpClient->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true);
            }
            
            return null;
            
        } catch (GuzzleException $e) {
            Yii::error('获取邮件详情失败: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    /**
     * 删除邮件
     * 
     * @param string $messageId 邮件ID
     * @param string $userId 用户ID（可选）
     * @return bool
     */
    public function deleteMessage($messageId, $userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/messages/{$messageId}");
            
            $response = $this->_httpClient->delete($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            
            return $response->getStatusCode() === 204;
            
        } catch (GuzzleException $e) {
            Yii::error('删除邮件失败: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * 标记邮件为已读
     * 
     * @param string $messageId 邮件ID
     * @param string $userId 用户ID（可选）
     * @return bool
     */
    public function markAsRead($messageId, $userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/messages/{$messageId}");
            
            $response = $this->_httpClient->patch($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'isRead' => true,
                ],
            ]);
            
            return $response->getStatusCode() === 200;
            
        } catch (GuzzleException $e) {
            Yii::error('标记邮件为已读失败: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * 创建邮件草稿
     * 
     * @param array $mailData 邮件数据
     * @param string $userId 用户ID（可选）
     * @return array|null
     */
    public function createDraft($mailData, $userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/messages");
            
            $response = $this->_httpClient->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $mailData,
            ]);
            
            if ($response->getStatusCode() === 201) {
                return json_decode($response->getBody()->getContents(), true);
            }
            
            return null;
            
        } catch (GuzzleException $e) {
            Yii::error('创建邮件草稿失败: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    /**
     * 发送草稿邮件
     * 
     * @param string $messageId 邮件ID
     * @param string $userId 用户ID（可选）
     * @return bool
     */
    public function sendDraft($messageId, $userId = null)
    {
        try {
            $accessToken = $this->config->getAccessToken();
            $userId = $userId ?: $this->config->userEmail;
            $endpoint = $this->config->getGraphApiUrl("users/{$userId}/messages/{$messageId}/send");
            
            $response = $this->_httpClient->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
            
            return $response->getStatusCode() === 202;
            
        } catch (GuzzleException $e) {
            Yii::error('发送草稿邮件失败: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * 测试API连接
     * 
     * @return bool
     */
    public function testConnection()
    {
        try {
            $userInfo = $this->getUserInfo();
            return $userInfo !== null;
        } catch (\Exception $e) {
            Yii::error('测试API连接失败: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
