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

/**
 * Class BannerModel
 *
 * @property int    $bannerId
 * @property string $bannerName
 * @property string $bannerImg
 * @property string $bannerDescription
 * @property string $bannerUrl
 * @property int    $state
 */
class BannerModel extends BaseModel
{
    protected string $table = 'banner_list';
    protected string $primaryKey = 'bannerId';

    #[Property(isPrimaryKey: true)]
    public int $bannerId;
    #[Property]
    public string $bannerName;
    #[Property]
    public string $bannerImg;
    #[Property]
    public string $bannerDescription;
    #[Property]
    public string $bannerUrl;
    #[Property]
    public int $state;

    public function getAll(int $page = 1, int $state = 1, ?string $keyword = null, int $pageSize = 10): array
    {
        $where = [];
        if (!empty($keyword)) {
            $where['bannerUrl'] = ['%' . $keyword . '%', 'like'];
        }

        $where['state'] = $state;

        $this->queryLimit()->page($page, withTotalCount: true, pageSize: $pageSize)
            ->orderBy($this->primaryKey, 'DESC');
        /** \EasySwoole\FastDb\Beans\ListResult $resultList */
        $listResult = $this->where($where)->all();
        $total = $listResult->totalCount();
        $list = $listResult->list();

        return ['total' => $total, 'list' => $list];
    }
}
