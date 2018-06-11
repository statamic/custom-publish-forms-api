<?php

namespace Statamic\Addons\Products;

use Statamic\Extend\Tags;

class ProductsTags extends Tags
{
    protected $products;

    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }

    public function all()
    {
        return $this->parseLoop(
            $this->products->all()
        );
    }

    public function find()
    {
        return $this->parse(
            $this->products->findBySlug($this->context['product'])
        );
    }
}
