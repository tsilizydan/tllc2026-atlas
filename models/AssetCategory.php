<?php
/**
 * TSILIZY CORE - Asset Category Model
 */

class AssetCategory extends Model
{
    protected static string $table = 'asset_categories';
    protected static array $fillable = [
        'name', 'slug', 'icon', 'description', 'color'
    ];

    /**
     * Return an associative array suitable for <select> dropdowns: [id => name]
     */
    public static function dropdown(): array
    {
        $rows = Database::fetchAll(
            "SELECT id, name FROM asset_categories ORDER BY name ASC"
        );
        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['id']] = $row['name'];
        }
        return $result;
    }
}
