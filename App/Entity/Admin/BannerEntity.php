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

namespace App\Entity\Admin;

use App\Entity\BaseEntity;
use EasySwoole\FastDb\Attributes\Property;

/**
 * Class BannerEntity
 *
 * @property int    $bannerId
 * @property string $bannerName
 * @property string $bannerImg
 * @property string $bannerDescription
 * @property string $bannerUrl
 * @property int    $state
 */
class BannerEntity extends BaseEntity
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

        /** \EasySwoole\FastDb\Beans\ListResult $listResult */
        $listResult = $this->page($page, true, $pageSize)
            ->orderBy($this->primaryKey, 'DESC')
            ->where($where)
            ->all();
        $total = $listResult->totalCount();
        $list = $listResult->list();

        return ['total' => $total, 'list' => $list];
    }
}
