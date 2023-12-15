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

namespace App\HttpController\Api\Common;

use App\Entity\Admin\BannerEntity;
use EasySwoole\Http\Message\Status;
use EasySwoole\HttpAnnotation\Attributes\Api;
use EasySwoole\HttpAnnotation\Attributes\Description;
use EasySwoole\HttpAnnotation\Attributes\Param;
use EasySwoole\HttpAnnotation\Enum\HttpMethod;
use EasySwoole\HttpAnnotation\Enum\ParamFrom;
use EasySwoole\HttpAnnotation\Validator\Integer;
use EasySwoole\HttpAnnotation\Validator\MaxLength;
use EasySwoole\HttpAnnotation\Validator\Optional;
use EasySwoole\HttpAnnotation\Validator\Required;

class Banner extends CommonBase
{
    #[Api(
        apiName: 'bannerGetOne',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/common/banner/getOne',
        requestParam: [
        new Param(name: 'bannerId', from: ParamFrom::GET, validate: [
            new Required(),
            new Integer(),
        ], description: new Description('主键id')),
    ],
        description: 'getOne'
    )]
    /**
     * Author: XueSi <hui.huang8540@gmail.com>
     * Time: 00:20
     */
    public function getOne()
    {
        $param = $this->request()->getRequestParam();
        $entity = new BannerEntity();
        $bean = $entity->getOne($param['bannerId']);
        if ($bean) {
            $this->writeJson(Status::CODE_OK, $bean, 'success');
        } else {
            $this->writeJson(Status::CODE_BAD_REQUEST, [], 'fail');
        }
    }

    #[Api(
        apiName: 'bannerGetAll',
        allowMethod: HttpMethod::GET,
        requestPath: '/api/common/banner/getAll',
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
     * Time: 00:20
     */
    public function getAll()
    {
        $param = $this->request()->getRequestParam();
        $page = (int)$this->input('page', 1);
        $limit = (int)$this->input('limit', 20);
        $entity = new BannerEntity();
        $data = $entity->getAll($page, 1, $param['keyword'] ?? null, $limit);
        $this->writeJson(Status::CODE_OK, $data, 'success');
    }
}
