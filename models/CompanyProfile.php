<?php
/**
 * TSILIZY CORE - CompanyProfile Model
 */

class CompanyProfile extends Model
{
    protected static string $table = 'company_profile';
    protected static array $fillable = [
        'company_name', 'legal_name', 'tax_id', 'registration_number',
        'address', 'city', 'country', 'phone', 'email', 'website',
        'logo_path', 'favicon_path', 'primary_color', 'secondary_color',
        'social_facebook', 'social_twitter', 'social_linkedin', 'social_instagram',
        'footer_text'
    ];

    /**
     * Get the company profile
     */
    public static function get(): ?array
    {
        return Database::fetch("SELECT * FROM company_profile ORDER BY id ASC LIMIT 1");
    }

    /**
     * Update or create company profile
     */
    public static function updateProfile(array $data): bool
    {
        $profile = self::get();
        
        if ($profile) {
            return self::update($profile['id'], $data);
        }
        
        return self::create($data) > 0;
    }

    /**
     * Update logo
     */
    public static function updateLogo(string $path): bool
    {
        $profile = self::get();
        if (!$profile) return false;
        
        return self::update($profile['id'], ['logo_path' => $path]);
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl(): string
    {
        $profile = self::get();
        return upload($profile['logo_path'] ?? null, 'logo');
    }
}
