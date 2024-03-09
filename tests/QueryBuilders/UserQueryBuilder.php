<?php

namespace Tests\QueryBuilders;

use AlexMandrik\AdvancedQueryBuilder\AdvancedQueryBuilder;

class UserQueryBuilder extends AdvancedQueryBuilder
{
    public function nameContains(string $needle): self
    {
        return $this->where('name', 'like', "%{$needle}%");
    }
}
