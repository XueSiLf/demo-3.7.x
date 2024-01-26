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
use EasySwoole\FastDb\Exception\RuntimeError;
use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\Attributes\Api;
use EasySwoole\HttpAnnotation\Attributes\Description;
use EasySwoole\HttpAnnotation\Attributes\Param;
use EasySwoole\HttpAnnotation\Enum\HttpMethod;
use EasySwoole\HttpAnnotation\Enum\ParamFrom;
use EasySwoole\HttpAnnotation\Exception\Annotation;
use EasySwoole\HttpAnnotation\Validator\MaxLength;
use EasySwoole\HttpAnnotation\Validator\MinLength;
use EasySwoole\HttpAnnotation\Validator\Required;

class Auth extends AdminBase
{
    protected array $whiteList = ['login'];

    /**
     * @return void
     * @throws RuntimeError
     * @throws Annotation
     */
    #[Api(
        apiName: 'login',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/auth/login',
        requestParam: [
        new Param(name: 'account', from: ParamFrom::GET, validate: [
            new Required(),
            new MaxLength(20)
        ], description: new Description('帐号')),
        new Param(name: 'password', from: ParamFrom::GET, validate: [
            new Required(),
            new MinLength(6),
            new MaxLength(16),
        ], description: new Description('密码')),
    ],
        description: '登陆,参数验证注解写法'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:31
     */
    public function login()
    {
        $param = $this->request()->getRequestParam();
        $model = new AdminModel();
        $model->adminAccount = $param['account'];
        $model->adminPassword = md5($param['password']);

        if ($user = $model->login()) {
            $sessionHash = md5(time() . $user->adminId);
            $user->updateWithLimit([
                'adminLastLoginTime' => time(),
                'adminLastLoginIp'   => $this->clientRealIP(),
                'adminSession'       => $sessionHash
            ]);

            $rs = $user->toArray();
            unset($rs['adminPassword']);
            $rs['adminSession'] = $sessionHash;
            $this->response()->setCookie('adminSession', $sessionHash, time() + 3600, '/');
            $this->writeJson(Status::CODE_OK, $rs);
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, '', '密码错误');
        }
    }

    #[Api(
        apiName: 'logout',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/auth/logout',
        requestParam: [
        new Param(name: 'adminSession', from: ParamFrom::COOKIE, validate: [
            new Required(),
        ], description: new Description('帐号')),
    ],
        description: '退出登录,参数注解写法'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:32
     *
     * @throws Annotation
     */
    public function logout()
    {
        $sessionKey = $this->request()->getRequestParam($this->sessionKey);
        if (empty($sessionKey)) {
            $sessionKey = $this->request()->getCookieParams('adminSession');
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
        requestPath: '/api/admin/auth/getInfo',
        description: '获取管理员信息'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:34
     */
    public function getInfo()
    {
        $this->writeJson(200, $this->getWho()->toArray(), 'success');
    }
}
