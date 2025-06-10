# Latin Name Implementation for Bagisto Products

## Overview
This implementation adds a `latin_name` field to products in Bagisto, which is accessible through the REST API as `name_latin`. This allows products to have both their original names and Latin/English equivalents.

## What Was Implemented

### 1. Database Changes
- Added `latin_name` column to `product_flat` table
- Created `latin_name` attribute in the attributes system
- Mapped the attribute to all attribute families in the "General" group

### 2. API Modifications
- **REST API**: Modified `/vendor/bagisto/rest-api/src/Http/Resources/V1/Shop/Catalog/ProductResource.php`
- **Shop API**: Modified `/packages/Webkul/Shop/src/Http/Resources/ProductResource.php`
- Both now include `name_latin` field in the JSON response

### 3. Admin Interface
- The `latin_name` field now automatically appears in the admin product creation/edit forms
- It's part of the "General" attribute group and displays as a text input field

## Usage

### API Response Format
```json
{
  "id": 1,
  "name": "قبعة الصوف المريحة القطبية للجنسين",
  "name_latin": "Arctic Cozy Knit Unisex Beanie",
  "sku": "SP-001",
  "price": "14.0000",
  ...
}
```

### API Endpoints
- **Single Product**: `GET /api/v1/products/{id}`
- **Products List**: `GET /api/v1/products`

### Admin Usage
1. Go to Admin → Catalog → Products
2. Create or edit a product
3. In the "General" section, you'll find the "Latin Name" field
4. Enter the Latin/English equivalent of the product name
5. Save the product

## Technical Details

### Database Schema
```sql
-- product_flat table
ALTER TABLE product_flat ADD COLUMN latin_name VARCHAR(255) NULL AFTER name;

-- attributes table  
INSERT INTO attributes (code, admin_name, type, position, is_user_defined, value_per_locale, ...)
VALUES ('latin_name', 'Latin Name', 'text', 4, 1, 1, ...);
```

### Code Changes

#### REST API Resource
```php
// File: vendor/bagisto/rest-api/src/Http/Resources/V1/Shop/Catalog/ProductResource.php
'name_latin' => $product->product_flats->first()?->latin_name,
```

#### Shop API Resource  
```php
// File: packages/Webkul/Shop/src/Http/Resources/ProductResource.php
'name_latin' => $this->product_flats->first()?->latin_name,
```

## Migration Files
- `database/migrations/2025_06_10_160000_add_latin_name_column_to_product_flat_table.php`
- `database/migrations/2025_06_10_160001_add_latin_name_attribute.php`

## Testing

### Test API Response
```bash
curl -X GET "http://localhost:8000/api/v1/products/1" -H "Accept: application/json" | jq '.data.name_latin'
```

### Test Database
```sql
SELECT id, name, latin_name FROM product_flat WHERE latin_name IS NOT NULL;
```

## Notes
- The `latin_name` field is optional (nullable)
- Products without a latin_name will return `null` in the API
- The field is locale-specific (value_per_locale = 1)
- Changes are backward compatible and won't affect existing functionality
- The attribute is automatically available in all product attribute families

## Maintenance
- The feature requires no additional maintenance
- Standard Bagisto attribute management applies
- The field can be modified through Admin → Catalog → Attributes if needed
