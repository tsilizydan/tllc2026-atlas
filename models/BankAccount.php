<?php
/**
 * TSILIZY CORE - BankAccount Model
 */

class BankAccount extends Model
{
    protected static string $table = 'bank_accounts';
    protected static array $fillable = [
        'bank_name', 'account_name', 'account_number',
        'account_type', 'balance', 'is_primary', 'is_active'
    ];

    /**
     * Get active accounts
     */
    public static function active(): array
    {
        return self::where('is_active = 1', [], 'is_primary DESC, bank_name ASC');
    }

    /**
     * Get primary account
     */
    public static function getPrimary(): ?array
    {
        return self::first('is_primary = 1 AND is_active = 1');
    }

    /**
     * Set as primary account
     */
    public static function setPrimary(int $id): bool
    {
        // Remove primary from all
        Database::query("UPDATE bank_accounts SET is_primary = 0");
        
        // Set new primary
        return self::update($id, ['is_primary' => 1]);
    }

    /**
     * Get total balance
     */
    public static function getTotalBalance(): float
    {
        return (float) Database::fetchColumn(
            "SELECT COALESCE(SUM(balance), 0) FROM bank_accounts WHERE is_active = 1"
        );
    }

    /**
     * Update balance
     */
    public static function updateBalance(int $id, float $amount, string $type = 'add'): bool
    {
        $account = self::find($id);
        if (!$account) return false;
        
        $newBalance = $type === 'add' 
            ? $account['balance'] + $amount 
            : $account['balance'] - $amount;
        
        return self::update($id, ['balance' => $newBalance]);
    }
}
