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
use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\Attributes\Api;
use EasySwoole\HttpAnnotation\Attributes\Description;
use EasySwoole\HttpAnnotation\Attributes\Param;
use EasySwoole\HttpAnnotation\Enum\HttpMethod;
use EasySwoole\HttpAnnotation\Enum\ParamFrom;
use EasySwoole\HttpAnnotation\Validator\MaxLength;
use EasySwoole\HttpAnnotation\Validator\MinLength;
use EasySwoole\HttpAnnotation\Validator\Optional;
use EasySwoole\HttpAnnotation\Validator\Required;

class Auth extends UserBase
{
    protected array $whiteList = ['login'];

    #[Api(
        apiName: 'login',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/user/auth/login',
        requestParam: [
        new Param(name: 'userAccount', from: ParamFrom::GET, validate: [
            new Required(),
            new MaxLength(32)
        ], description: new Description('用户名')),
        new Param(name: 'userPassword', from: ParamFrom::GET, validate: [
            new Required(),
            new MinLength(6),
            new MaxLength(18),
        ], description: new Description('密码')),
    ],
        description: 'login'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:31
     */
    public function login()
    {
        $param = $this->request()->getRequestParam();
        $entity = new UserEntity();
        $entity->userAccount = $param['userAccount'];
        $entity->userPassword = md5($param['userPassword']);

        if ($userInfo = $entity->login()) {
            $sessionHash = md5(time() . $userInfo->userId);
            $userInfo->update([
                'lastLoginIp'   => $this->clientRealIP(),
                'lastLoginTime' => time(),
                'userSession'   => $sessionHash
            ]);
            $rs = $userInfo->toArray();
            unset($rs['userPassword']);
            $rs['userSession'] = $sessionHash;
            $this->response()->setCookie('userSession', $sessionHash, time() + 3600, '/');
            $this->writeJson(Status::CODE_OK, $rs);
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, '', '密码错误');
        }
    }

    #[Api(
        apiName: 'logout',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/user/auth/logout',
        requestParam: [
        new Param(name: 'userSession', from: ParamFrom::GET, validate: [
            new Optional(),
        ], description: new Description('用户会话')),
    ],
        description: 'logout'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 01:07
     */
    public function logout()
    {
        $sessionKey = $this->request()->getRequestParam('userSession');
        if (empty($sessionKey)) {
            $sessionKey = $this->request()->getCookieParams('userSession');
        }

        if (empty($sessionKey)) {
            $this->writeJson(Status::CODE_UNAUTHORIZED, '', '尚未登录');
            return false;
        }

        $result = $this->getWho()->logout();
        if ($result) {
            $this->writeJson(Status::CODE_OK, '', '退出登录成功');
        } else {
            $this->writeJson(Status::CODE_UNAUTHORIZED, '', 'fail');
        }
    }

    #[Api(
        apiName: 'getInfo',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/user/auth/getInfo',
        description: 'getInfo'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 01:10
     */
    public function getInfo()
    {
        $this->writeJson(200, $this->getWho(), 'success');
    }
}
