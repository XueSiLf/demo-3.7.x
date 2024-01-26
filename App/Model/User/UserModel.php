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

namespace App\Model\User;

use App\Model\BaseModel;
use EasySwoole\FastDb\Attributes\Property;

/**
 * Class UserModel
 *
 * @property int    $userId
 * @property string $userName
 * @property string $userAccount
 * @property string $userPassword
 * @property string $phone
 * @property int    $addTime
 * @property string $lastLoginIp
 * @property int    $lastLoginTime
 * @property string $userSession
 * @property int    $state
 * @property int    $money
 * @property int    $frozenMoney
 */
class UserModel extends BaseModel
{
    protected string $table = 'user_list';
    protected string $primaryKey = 'userId';

    public const STATE_PROHIBIT = 0; // 禁用状态
    public const STATE_NORMAL = 1; // 正常状态

    #[Property(isPrimaryKey: true)]
    public int $userId;
    #[Property]
    public string $userName;
    #[Property]
    public string $userAccount;
    #[Property]
    public string $userPassword;
    #[Property]
    public string $phone;
    #[Property]
    public int $addTime;
    #[Property]
    public ?string $lastLoginIp;
    #[Property]
    public ?int $lastLoginTime;
    #[Property]
    public ?string $userSession;
    #[Property]
    public int $state;
    #[Property]
    public int $money;
    #[Property]
    public int $frozenMoney;

    /**
     * @getAll
     *
     * @param int         $page
     * @param string|null $keyword
     * @param int         $pageSize
     *
     * @return array[total,list]
     */
    public function getAll(int $page = 1, ?string $keyword = null, int $pageSize = 10): array
    {
        $where = [];
        if (!empty($keyword)) {
            $where['userAccount'] = ['%' . $keyword . '%', 'like'];
        }

        $this->queryLimit()->page($page, withTotalCount: true, pageSize: $pageSize)
            ->orderBy($this->primaryKey, 'DESC');
        /** \EasySwoole\FastDb\Beans\ListResult $resultList */
        $resultList = $this
            ->where($where)
            ->all();

        $total = $resultList->totalCount();
        $list = $resultList->list();

        return ['total' => $total, 'list' => $list];
    }

    public function getOneByPhone(array $field = ['*']): ?UserModel
    {
        $this->queryLimit()->fields($field);
        return $this->find(['phone' => $this->phone]);
    }

    /*
    * 登录成功后请返回更新后的bean
    */
    public function login(): ?UserModel
    {
        return $this->find([
            'userAccount'  => $this->userAccount,
            'userPassword' => $this->userPassword
        ]);
    }

    public function getOneBySession(array $field = ['*']): ?UserModel
    {
        $this->queryLimit()->fields($field);
        return $this->find(['userSession' => $this->userSession]);
    }

    public function logout()
    {
        return $this->where([$this->primaryKey => $this->userId])->updateWithLimit(['userSession' => '']);
    }
}
