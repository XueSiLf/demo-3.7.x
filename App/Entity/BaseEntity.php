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

namespace App\Entity;

use EasySwoole\FastDb\Entity;
use EasySwoole\Mysqli\QueryBuilder;

class BaseEntity extends Entity
{
    protected string $table;
    protected string $primaryKey;

    public function tableName(): string
    {
        return $this->table;
    }

    protected function where(array $where): BaseEntity
    {
        $this->whereCall(function (QueryBuilder $queryBuilder) use ($where) {
            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    $queryBuilder->where($field, $value[0], $value[1]);
                } else {
                    $queryBuilder->where($field, $value);
                }
            }
        });

        return $this;
    }
}
