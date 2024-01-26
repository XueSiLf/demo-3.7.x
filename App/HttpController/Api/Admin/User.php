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

use App\Model\User\UserModel;
use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\Attributes\Api;
use EasySwoole\HttpAnnotation\Attributes\Description;
use EasySwoole\HttpAnnotation\Attributes\Param;
use EasySwoole\HttpAnnotation\Enum\HttpMethod;
use EasySwoole\HttpAnnotation\Enum\ParamFrom;
use EasySwoole\HttpAnnotation\Validator\InArray;
use EasySwoole\HttpAnnotation\Validator\Integer;
use EasySwoole\HttpAnnotation\Validator\IsPhoneNumber;
use EasySwoole\HttpAnnotation\Validator\MaxLength;
use EasySwoole\HttpAnnotation\Validator\MinLength;
use EasySwoole\HttpAnnotation\Validator\Optional;
use EasySwoole\HttpAnnotation\Validator\Required;

class User extends AdminBase
{
    #[Api(
        apiName: 'userGetAll',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/user/getAll',
        requestParam: [
        new Param(name: 'page', from: ParamFrom::GET, validate: [
            new Optional(),
            new Integer(),
        ], description: new Description('页数')),
        new Param(name: 'limit', from: ParamFrom::GET, validate: [
            new Optional(),
            new Integer(),
        ], description: new Description('每页总数')),
        new Param(name: 'keyword', from: ParamFrom::GET, validate: [
            new Optional(),
            new MaxLength(32),
        ], description: new Description('关键字')),
    ],
        description: 'getAll'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:38
     */
    public function getAll()
    {
        $page = (int)$this->input('page', 1);
        $limit = (int)$this->input('limit', 20);
        $model = new UserModel();
        $data = $model->getAll($page, $this->input('keyword'), $limit);
        $this->writeJson(Status::CODE_OK, $data, 'success');
    }

    #[Api(
        apiName: 'userGetOne',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/user/getOne',
        requestParam: [
        new Param(name: 'userId', from: ParamFrom::GET, validate: [
            new Required(),
            new Integer(),
        ], description: new Description('户id')),
    ],
        description: 'getAll'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:40
     */
    public function getOne()
    {
        $param = $this->request()->getRequestParam();
        $model = new UserModel();
        $rs = $model->find((int)$param['userId']);
        if ($rs) {
            $this->writeJson(Status::CODE_OK, $rs, 'success');
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, [], 'fail');
        }
    }

    #[Api(
        apiName: 'addUser',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/user/add',
        requestParam: [
        new Param(name: 'userName', from: ParamFrom::GET, validate: [
            new Optional(),
            new MaxLength(32),
        ], description: new Description('用户昵称')),
        new Param(name: 'userAccount', from: ParamFrom::GET, validate: [
            new Required(),
            new MaxLength(32),
        ], description: new Description('用户名')),
        new Param(name: 'userPassword', from: ParamFrom::GET, validate: [
            new Required(),
            new MinLength(6),
            new MaxLength(18),
        ], description: new Description('用户密码')),
        new Param(name: 'phone', from: ParamFrom::GET, validate: [
            new Optional(),
            new IsPhoneNumber(),
        ], description: new Description('手机号码')),
        new Param(name: 'state', from: ParamFrom::GET, validate: [
            new Optional(),
            new InArray([0, 1]),
        ], description: new Description('用户状态')),
    ],
        description: 'add'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:44
     */
    public function add()
    {
        $param = $this->request()->getRequestParam();
        $model = new UserModel($param);
        $model->userPassword = md5($param['userPassword']);
        $rs = $model->insert();
        if ($rs) {
            $this->writeJson(Status::CODE_OK, $rs, 'success');
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, [], 'add fail');
        }
    }

    #[Api(
        apiName: 'updateUser',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/user/update',
        requestParam: [
        new Param(name: 'userId', from: ParamFrom::GET, validate: [
            new Required(),
            new Integer(),
        ], description: new Description('用户id')),
        new Param(name: 'userPassword', from: ParamFrom::GET, validate: [
            new Optional(),
            new MinLength(6),
            new MaxLength(18),
        ], description: new Description('会员密码')),
        new Param(name: 'userName', from: ParamFrom::GET, validate: [
            new Optional(),
            new MaxLength(32),
        ], description: new Description('会员名')),
        new Param(name: 'state', from: ParamFrom::GET, validate: [
            new Optional(),
            new InArray([0, 1]),
        ], description: new Description('状态')),
        new Param(name: 'phone', from: ParamFrom::GET, validate: [
            new Optional(),
            new IsPhoneNumber(),
        ], description: new Description('手机号')),
    ],
        description: 'update'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:50
     */
    public function update()
    {
        $model = new UserModel();
        $userId = $this->input('userId');
        /**
         * @var $userInfo UserModel
         */
        $userInfo = $model->find((int)$userId);
        if (!$userInfo) {
            $this->writeJson(Status::CODE_BAD_REQUEST, [], '未找到该会员');
            return false;
        }

        $password = $this->input('userPassword');
        $update = [
            'userName'     => $this->input('userName', $userInfo->userName),
            'userPassword' => $password ? md5($password) : $userInfo->userPassword,
            'state'        => $this->input('state', $userInfo->state),
            'phone'        => $this->input('phone', $userInfo->phone),
        ];

        $rs = $userInfo->updateWithLimit($update);
        if ($rs === 0 || $rs === 1) {
            $this->writeJson(Status::CODE_OK, $rs, 'success');
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, [], 'update fail');
        }
    }

    #[Api(
        apiName: 'deleteUser',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/admin/user/delete',
        requestParam: [
        new Param(name: 'userId', from: ParamFrom::GET, validate: [
            new Required(),
            new Integer(),
        ], description: new Description('用户id')),
    ],
        description: 'delete'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:55
     */
    public function delete()
    {
        $param = $this->request()->getRequestParam();
        $model = new UserModel(['userId' => $param['userId']]);
        $rs = $model->delete();
        if ($rs) {
            $this->writeJson(Status::CODE_OK, $rs, 'success');
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, [], '删除失败');
        }
    }
}
