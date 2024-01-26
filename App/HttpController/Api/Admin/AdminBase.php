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

namespace App\HttpController\Api\Admin;

use App\Model\Admin\AdminModel;
use App\HttpController\Api\ApiBase;
use EasySwoole\Http\Message\Status;

class AdminBase extends ApiBase
{
    // public 才会根据协程清除
    public ?AdminModel $who;

    // session 的 cookie头
    protected string $sessionKey = 'adminSession';

    // 白名单
    protected array $whiteList = [];

    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:28
     */
    public function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            // 白名单判断
            if (in_array($action, $this->whiteList)) {
                return true;
            }

            // 获取登录信息
            if (!$this->getWho()) {
                $this->writeJson(Status::CODE_UNAUTHORIZED, '', '登录已过期');
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:28
     */
    protected function getWho(): ?AdminModel
    {
        if (isset($this->who) && $this->who instanceof AdminModel) {
            return $this->who;
        }

        $sessionKey = $this->request()->getRequestParam($this->sessionKey);
        if (empty($sessionKey)) {
            $sessionKey = $this->request()->getCookieParams($this->sessionKey);
        }

        if (empty($sessionKey)) {
            return null;
        }

        $adminModel = new AdminModel();
        $adminModel->adminSession = $sessionKey;
        $this->who = $adminModel->getOneBySession();

        return $this->who;
    }
}
