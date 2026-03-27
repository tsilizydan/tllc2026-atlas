<?php
/**
 * TSILIZY CORE - Service Model
 */

class Service extends Model
{
    protected static string $table = 'services';
    protected static array $fillable = [
        'name', 'description', 'icon', 'price_range', 'is_active', 'sort_order'
    ];

    /**
     * Get active services
     */
    public static function active(): array
    {
        return self::where('is_active = 1', [], 'sort_order ASC, name ASC');
    }

    /**
     * Reorder services
     */
    public static function reorder(array $order): bool
    {
        foreach ($order as $position => $id) {
            self::update($id, ['sort_order' => $position]);
        }
        return true;
    }

    /**
     * Toggle service status
     */
    public static function toggleStatus(int $id): bool
    {
        $service = self::find($id);
        if (!$service) return false;
        
        return self::update($id, [
            'is_active' => $service['is_active'] ? 0 : 1
        ]);
    }
}
