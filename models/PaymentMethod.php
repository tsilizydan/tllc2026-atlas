<?php
/**
 * TSILIZY CORE - PaymentMethod Model
 */

class PaymentMethod extends Model
{
    protected static string $table = 'payment_methods';
    protected static array $fillable = [
        'name', 'type', 'details', 'is_active'
    ];

    /**
     * Get active payment methods
     */
    public static function active(): array
    {
        return self::where('is_active = 1', [], 'name ASC');
    }

    /**
     * Get for dropdown
     */
    public static function dropdown(): array
    {
        return Database::fetchAll(
            "SELECT id, name, type FROM payment_methods WHERE is_active = 1 ORDER BY name ASC"
        );
    }

    /**
     * Toggle status
     */
    public static function toggleStatus(int $id): bool
    {
        $method = self::find($id);
        if (!$method) return false;
        
        return self::update($id, [
            'is_active' => $method['is_active'] ? 0 : 1
        ]);
    }
}
