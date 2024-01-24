<?php
/**
 * This file is part of EasySwoole.
 *
 * @link    https://www.easyswoole.com
 * @document https://www.easyswoole.com
 * @contact https://www.easyswoole.com/Preface/contact.html
 * @license https://github.com/easy-swoole/easyswoole/blob/3.x/LICENSE
 */
declare(strict_types=1);

namespace App\HttpController;

use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\HttpAnnotation\AnnotationController;

class Base extends AnnotationController
{
    public function index(): void
    {
        $this->actionNotFound('index');
    }

    /**
     * 获取用户的真实IP
     *
     * @param string $headerName 代理服务器传递的标头名称
     *
     * @return string|null
     */
    protected function clientRealIP(string $headerName = 'x-real-ip'): ?string
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $client = $server->getClientInfo($this->request()->getSwooleRequest()->fd);
        $clientAddress = $client['remote_ip'];
        $xri = $this->request()->getHeaderLine($headerName);
        $xff = $this->request()->getHeaderLine('x-forwarded-for');
        if ($clientAddress === '127.0.0.1') {
            if (!empty($xri)) {  // 如果有 xri 则判定为前端有 NGINX 等代理
                $clientAddress = $xri;
            } elseif (!empty($xff)) {  // 如果不存在 xri 则继续判断 xff
                $clientAddress = $xff;
            }
        }

        return $clientAddress;
    }

    protected function input(string $name, mixed $default = null)
    {
        $value = $this->request()->getRequestParam($name);
        return $value ?? $default;
    }
}
