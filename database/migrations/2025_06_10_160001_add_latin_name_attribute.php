<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert the latin_name attribute
        DB::table('attributes')->insert([
            'code'                => 'latin_name',
            'admin_name'          => 'Latin Name',
            'type'                => 'text',
            'position'            => 4, // Position after 'name' attribute
            'is_required'         => 0,
            'is_unique'           => 0,
            'is_filterable'       => 0,
            'is_comparable'       => 1,
            'is_configurable'     => 0,
            'is_user_defined'     => 1,
            'is_visible_on_front' => 0,
            'value_per_locale'    => 1,
            'value_per_channel'   => 0,
            'default_value'       => null,
            'enable_wysiwyg'      => 0,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        ]);

        $attributeId = DB::getPdo()->lastInsertId();

        // Add the attribute to all existing attribute families
        $attributeFamilies = DB::table('attribute_families')->get();

        foreach ($attributeFamilies as $family) {
            // Find the "General" group for this family
            $generalGroup = DB::table('attribute_groups')
                ->where('name', 'General')
                ->where('attribute_family_id', $family->id)
                ->first();

            if ($generalGroup) {
                // Get the current max position in the General group
                $maxPosition = DB::table('attribute_group_mappings')
                    ->where('attribute_group_id', $generalGroup->id)
                    ->max('position') ?? 0;

                // Add the latin_name attribute to the General group
                DB::table('attribute_group_mappings')->insert([
                    'attribute_group_id' => $generalGroup->id,
                    'attribute_id'       => $attributeId,
                    'position'           => $maxPosition + 1,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get the attribute ID
        $attribute = DB::table('attributes')
            ->where('code', 'latin_name')
            ->first();

        if ($attribute) {
            // Remove from attribute group mappings
            DB::table('attribute_group_mappings')
                ->where('attribute_id', $attribute->id)
                ->delete();

            // Remove the attribute
            DB::table('attributes')
                ->where('id', $attribute->id)
                ->delete();
        }
    }
};
