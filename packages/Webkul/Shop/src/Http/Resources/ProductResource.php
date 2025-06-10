<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Product\Helpers\Review;

class ProductResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->reviewHelper = app(Review::class);

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $productTypeInstance = $this->getTypeInstance();

        return [
            'id'                    => $this->id,
            'sku'                   => $this->sku,
            'type'                  => $this->type,
            'name'                  => $this->name,
            'name_latin'            => $this->product_flats->first()?->latin_name,
            'url_key'               => $this->url_key,
            'price'                 => core()->formatPrice($productTypeInstance->getMinimalPrice()),
            'short_description'     => $this->short_description,
            'description'           => $this->description,
            'quantity'              => $productTypeInstance->totalQuantity(),
            'categories'            => $this->getCategoriesData(),
            'colors'                => $this->getAttributeOptions('color'),
            'sizes'                 => $this->getAttributeOptions('size'),
            'images'                => $this->getImagesData(),
            'videos'                => $this->getVideosData(),
            'base_image'            => $this->getBaseImageData(),
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'in_stock'              => (bool) $productTypeInstance->isSaleable(),
            'is_saved'              => (bool) (auth()->guard()->user() 
                ? auth()->guard()->user()->wishlist_items
                    ->where('channel_id', core()->getCurrentChannel()->id)
                    ->where('product_id', $this->id)->count() > 0 
                : false),
            'is_item_in_cart'       => null,
            'show_quantity_changer' => (bool) $productTypeInstance->showQuantityBox(),
        ];
    }

    /**
     * Get categories data for the product.
     *
     * @return array
     */
    protected function getCategoriesData()
    {
        return $this->categories->map(function ($category) {
            return [
                'id'   => $category->id,
                'name' => $category->name,
            ];
        })->toArray();
    }

    /**
     * Get attribute options for specific attribute.
     *
     * @param string $attributeCode
     * @return array
     */
    protected function getAttributeOptions($attributeCode)
    {
        $attributeValues = $this->attribute_values->where('attribute.code', $attributeCode);
        
        if ($attributeValues->isEmpty()) {
            return [];
        }

        $results = [];
        
        foreach ($attributeValues as $attributeValue) {
            if (!$attributeValue->attribute) {
                continue;
            }

            if ($attributeValue->attribute->type === 'select' || $attributeValue->attribute->type === 'multiselect') {
                // Get the option value from either text_value or integer_value
                $optionValue = $attributeValue->text_value ?: $attributeValue->integer_value;
                
                if (!$optionValue) {
                    continue;
                }

                $optionIds = is_string($optionValue) ? explode(',', $optionValue) : [$optionValue];
                
                foreach ($optionIds as $optionId) {
                    $option = $attributeValue->attribute->options->where('id', $optionId)->first();
                    if ($option) {
                        $results[] = [
                            'id'   => $option->id,
                            'name' => $option->admin_name,
                        ];
                    }
                }
            }
        }

        return array_unique($results, SORT_REGULAR);
    }

    /**
     * Get images data for the product.
     *
     * @return array
     */
    protected function getImagesData()
    {
        return $this->images->map(function ($image) {
            return [
                'id'                 => $image->id,
                'path'               => $image->path,
                'url'                => asset('storage/' . $image->path),
                'original_image_url' => asset('storage/' . $image->path),
                'small_image_url'    => url('cache/small/' . $image->path),
                'medium_image_url'   => url('cache/medium/' . $image->path),
                'large_image_url'    => url('cache/large/' . $image->path),
            ];
        })->toArray();
    }

    /**
     * Get videos data for the product.
     *
     * @return array
     */
    protected function getVideosData()
    {
        return $this->videos->map(function ($video) {
            return [
                'id'   => $video->id,
                'type' => $video->type,
                'path' => $video->path,
                'url'  => asset('storage/' . $video->path),
            ];
        })->toArray();
    }

    /**
     * Get base image data for the product.
     *
     * @return array|null
     */
    protected function getBaseImageData()
    {
        $baseImage = $this->images->first();
        
        if (!$baseImage) {
            return null;
        }

        return [
            'small_image_url'    => url('cache/small/' . $baseImage->path),
            'medium_image_url'   => url('cache/medium/' . $baseImage->path),
            'large_image_url'    => url('cache/large/' . $baseImage->path),
            'original_image_url' => url('cache/original/' . $baseImage->path),
        ];
    }
}
