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

namespace App\Model\Admin;

use App\Model\BaseModel;
use EasySwoole\FastDb\Attributes\Property;
use EasySwoole\FastDb\Beans\Query;

/**
 * Class AdminModel
 *
 * @property int    $adminId
 * @property string $adminName
 * @property string $adminAccount
 * @property string $adminPassword
 * @property string $adminSession
 * @property int    $adminLastLoginTime
 * @property string $adminLastLoginIp
 */
class AdminModel extends BaseModel
{
    #[Property(isPrimaryKey: true)]
    public int $adminId;
    #[Property]
    public string $adminName;
    #[Property]
    public string $adminAccount;
    #[Property]
    public string $adminPassword;
    #[Property]
    public string $adminSession;
    #[Property]
    public int $adminLastLoginTime;
    #[Property]
    public string $adminLastLoginIp;

    protected string $primaryKey = 'adminId';
    protected string $table = 'admin_list';

    /**
     * @getAll
     *
     * @param int         $page
     * @param null|string $keyword
     * @param int         $pageSize
     *
     * @return array[$total, $list]
     */
    public function getAll(int $page = 1, ?string $keyword = null, int $pageSize = 10): array
    {
        $where = [];
        if (!empty($keyword)) {
            $where['adminAccount'] = ['%' . $keyword . '%', 'like'];
        }

        $this->queryLimit()->page($page, true, $pageSize)
            ->orderBy($this->primaryKey, 'DESC');

        /** \EasySwoole\FastDb\Beans\ListResult $resultList */
        $resultList = $this->where($where)->all();

        $total = $resultList->totalCount();
        $list = $resultList->list();

        return ['total' => $total, 'list' => $list];
    }

    /**
     * 登录成功后请返回更新后的bean
     */
    public function login(): ?AdminModel
    {
        $where = [
            'adminAccount'  => $this->adminAccount,
            'adminPassword' => $this->adminPassword
        ];
        return self::findRecord($where);
    }

    /**
     * 以account进行查询
     */
    public function accountExist(array $field = ['*']): ?AdminModel
    {
        return self::findRecord(function (Query $query) use ($field) {
            $query->fields($field)
                ->where('adminAccount', $this->adminAccount);
        });
    }

    public function getOneBySession(array $field = ['*']): ?AdminModel
    {
        $this->queryLimit()->fields($field);
        $this->where(['adminSession' => $this->adminSession]);
        return $this->find();
    }

    public function logout()
    {
        $where = [$this->primaryKey => $this->adminId];
        $update = ['adminSession' => ''];
        return self::fastUpdate($where, $update);
    }
}
