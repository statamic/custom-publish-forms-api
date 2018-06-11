<?php

namespace Statamic\Addons\Products;

use Statamic\API\Fieldset;
use Illuminate\Http\Request;
use Statamic\Extend\Controller;
use Statamic\CP\Publish\ProcessesFields;

class ProductsController extends Controller
{
    use ProcessesFields;

    protected $products;

    public function __construct(ProductRepository $repo)
    {
        $this->products = $repo;
    }

    /**
     * Listing of all products
     */
    public function index()
    {
        return $this->view('index', [
            'title' => 'Products',
            'products' => $this->products->all()
        ]);
    }

    /**
     * The form to create a new product
     */
    public function create()
    {
        return $this->view('create', [
            'title' => 'New Product',
            'data' => $this->prepareData([]),
        ]);
    }

    /**
     * Endpoint for storing a new product
     */
    public function store(Request $request)
    {
        $data = $this->processFields($this->fieldset(), $request->fields);

        $response = $this->products->store($data);

        if (! $response['success']) {
            return $response;
        }

        return $this->successResponse($response['product']['id']);
    }

    /**
     * The form to edit an existing product
     */
    public function edit($id)
    {
        $product = $this->products->find($id);

        return $this->view('edit', [
            'title' => $product['title'],
            'product' => $product,
            'data' => $this->prepareData($product),
        ]);
    }

    /**
     * Endpoint for updating an existing product
     */
    public function update(Request $request, $id)
    {
        return $this->products->update($id, $request->fields);
    }

    /**
     * Prepare data to be used in the publish form.
     * It will add nulls (or appropriate default values) for any fields defined in the fieldset
     * that haven't been provided in the array. Vue needs the values to exist for reactivity.
     */
    private function prepareData($data)
    {
        return $this->preProcessWithBlankFields($this->fieldset(), $data);
    }

    /**
     * Get the fieldset.
     */
    private function fieldset()
    {
        return Fieldset::get('product');
    }

    /**
     * The response to be returned for a successful save.
     */
    private function successResponse($id)
    {
        $message = 'Product saved';

        // Actions that trigger an actual redirect should add the message into the session's flash data.
        if (! request()->continue || request()->new) {
            $this->success($message);
        }

        return [
            'success'  => true,
            'redirect' => request()->continue
                ? route('products.edit', ['product' => $id])
                : route('products.index'),
            'message' => $message
        ];
    }
}
