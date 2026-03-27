<?php
/**
 * TSILIZY CORE - Invoice Model
 */

class Invoice extends Model
{
    protected static string $table = 'invoices';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'invoice_number', 'client_id', 'project_id', 'issue_date', 'due_date',
        'status', 'subtotal', 'tax_rate', 'tax_amount', 'discount', 'total',
        'currency', 'notes', 'terms', 'created_by'
    ];

    /**
     * Get invoices with client info
     */
    public static function allWithClient(bool $includeArchived = false): array
    {
        $where = $includeArchived ? '1=1' : 'i.is_archived = 0';
        
        return Database::fetchAll(
            "SELECT i.*, c.company_name as client_name 
             FROM invoices i 
             LEFT JOIN clients c ON i.client_id = c.id 
             WHERE {$where} 
             ORDER BY i.created_at DESC"
        );
    }

    /**
     * Find invoice with full details
     */
    public static function findWithDetails(int $id): ?array
    {
        $invoice = Database::fetch(
            "SELECT i.*, c.company_name, c.contact_name, c.email as client_email, 
                    c.address as client_address, c.city as client_city, c.country as client_country,
                    p.name as project_name
             FROM invoices i 
             LEFT JOIN clients c ON i.client_id = c.id 
             LEFT JOIN projects p ON i.project_id = p.id 
             WHERE i.id = ?",
            [$id]
        );
        
        if ($invoice) {
            $invoice['items'] = self::getItems($id);
        }
        
        return $invoice;
    }

    /**
     * Get invoice items
     */
    public static function getItems(int $invoiceId): array
    {
        return Database::fetchAll(
            "SELECT * FROM invoice_items WHERE invoice_id = ? ORDER BY id ASC",
            [$invoiceId]
        );
    }

    /**
     * Add invoice item
     */
    public static function addItem(int $invoiceId, array $item): int
    {
        $item['invoice_id'] = $invoiceId;
        $item['total'] = ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
        $item['created_at'] = date(DATETIME_FORMAT);
        
        return Database::insert('invoice_items', $item);
    }

    /**
     * Update invoice item
     */
    public static function updateItem(int $itemId, array $data): bool
    {
        $data['total'] = ($data['quantity'] ?? 1) * ($data['unit_price'] ?? 0);
        return Database::update('invoice_items', $data, 'id = ?', [$itemId]) > 0;
    }

    /**
     * Delete invoice item
     */
    public static function deleteItem(int $itemId): bool
    {
        return Database::delete('invoice_items', 'id = ?', [$itemId]) > 0;
    }

    /**
     * Clear all invoice items
     */
    public static function clearItems(int $invoiceId): bool
    {
        return Database::delete('invoice_items', 'invoice_id = ?', [$invoiceId]) >= 0;
    }

    /**
     * Recalculate invoice totals
     */
    public static function recalculateTotals(int $invoiceId): bool
    {
        $invoice = self::find($invoiceId);
        if (!$invoice) return false;
        
        $subtotal = Database::fetchColumn(
            "SELECT COALESCE(SUM(total), 0) FROM invoice_items WHERE invoice_id = ?",
            [$invoiceId]
        );
        
        $taxAmount = ($subtotal * ($invoice['tax_rate'] ?? 0)) / 100;
        $total = $subtotal + $taxAmount - ($invoice['discount'] ?? 0);
        
        return self::update($invoiceId, [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total
        ]);
    }

    /**
     * Generate next invoice number
     */
    public static function generateNumber(): string
    {
        return generateInvoiceNumber();
    }

    /**
     * Get invoice statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'draft' => self::count("status = 'draft'"),
            'sent' => self::count("status = 'sent'"),
            'paid' => self::count("status = 'paid'"),
            'overdue' => self::count("status = 'overdue'"),
            'total_revenue' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(total), 0) FROM invoices WHERE status = 'paid' AND is_archived = 0"
            ),
            'outstanding' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(total), 0) FROM invoices WHERE status IN ('sent', 'overdue') AND is_archived = 0"
            )
        ];
    }

    /**
     * Get recent invoices
     */
    public static function recent(int $limit = 5): array
    {
        return Database::fetchAll(
            "SELECT i.*, c.company_name 
             FROM invoices i 
             LEFT JOIN clients c ON i.client_id = c.id 
             WHERE i.is_archived = 0 
             ORDER BY i.created_at DESC 
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Mark overdue invoices
     */
    public static function markOverdue(): int
    {
        return Database::query(
            "UPDATE invoices SET status = 'overdue' 
             WHERE status = 'sent' AND due_date < CURDATE() AND is_archived = 0"
        )->rowCount();
    }

    /**
     * Get by status
     */
    public static function byStatus(string $status): array
    {
        return Database::fetchAll(
            "SELECT i.*, c.company_name 
             FROM invoices i 
             LEFT JOIN clients c ON i.client_id = c.id 
             WHERE i.status = ? AND i.is_archived = 0 
             ORDER BY i.created_at DESC",
            [$status]
        );
    }
}
