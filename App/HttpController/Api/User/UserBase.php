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

namespace App\HttpController\Api\User;

use App\Entity\User\UserEntity;
use App\HttpController\Api\ApiBase;
use EasySwoole\Http\Message\Status;

class UserBase extends ApiBase
{
    protected ?UserEntity $who;

    // session 的 cookie 头
    protected string $sessionKey = 'userSession';

    // 白名单
    protected array $whiteList = ['login', 'register'];

    public function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            // 白名单判断
            if (in_array($action, $this->whiteList)) {
                return true;
            }

            // 获取登录信息
            if (!$data = $this->getWho()) {
                $this->writeJson(Status::CODE_UNAUTHORIZED, '', '登录已过期');
                return false;
            }

            // 刷新 cookie 存活
            $this->response()->setCookie($this->sessionKey, $data->userSession, time() + 3600, '/');

            return true;
        }

        return false;
    }

    public function getWho(): ?UserEntity
    {
        if (isset($this->who) && $this->who instanceof UserEntity) {
            return $this->who;
        }

        $sessionKey = $this->request()->getRequestParam($this->sessionKey);
        if (empty($sessionKey)) {
            $sessionKey = $this->request()->getCookieParams($this->sessionKey);
        }

        if (empty($sessionKey)) {
            return null;
        }

        $userEntity = new UserEntity();
        $userEntity->userSession = $sessionKey;
        $this->who = $userEntity->getOneBySession();

        return $this->who;
    }
}
