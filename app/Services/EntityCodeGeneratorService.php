<?php

namespace App\Services;

use Illuminate\Support\Str;

class EntityCodeGeneratorService
{
    /**
     * Build a resilient auto-generator for shortcodes (e.g. Categories, Departments).
     *
     * @param string $name         The original entity name.
     * @param string $modelClass   The Eloquent Model class string (e.g., Category::class).
     * @param int|null $propertyId Optional explicit property constraint. If null, relies on active scope.
     * @return string
     */
    public function generateUniqueCode(string $name, string $modelClass, ?int $propertyId = null): string
    {
        // 1. Clean the string: keep only letters and numbers
        $cleanName = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name));

        // Edge case fallback if totally non-alphanumeric
        if (strlen($cleanName) === 0) {
            $cleanName = 'ENT';
        }

        // Base code length of 3, pad with X if shorter
        $baseCode = str_pad(substr($cleanName, 0, 3), 3, 'X');

        if (! $this->codeExists($baseCode, $modelClass, $propertyId)) {
            return $baseCode;
        }

        // 3. Primary Fallback: Recursive alternate indices
        $len = strlen($cleanName);
        if ($len > 3) {
            for ($i = 3; $i < min($len, 10); $i++) {
                $altCode = substr($cleanName, 0, 2) . $cleanName[$i];
                if (! $this->codeExists($altCode, $modelClass, $propertyId)) {
                    return $altCode;
                }
            }
        }

        // 4. Secondary Fallback: Append sequential counter
        $counter = 1;
        while (true) {
            $seqCode = $baseCode . '-' . $counter;
            if (! $this->codeExists($seqCode, $modelClass, $propertyId)) {
                return $seqCode;
            }
            $counter++;
            
            // Safety break
            if ($counter > 500) {
                // Should realistically never hit this point unless extreme spam.
                return $baseCode . '-' . uniqid();
            }
        }
    }

    /**
     * Check if the generated code already exists for the given tenant property.
     */
    private function codeExists(string $code, string $modelClass, ?int $propertyId): bool
    {
        $query = $modelClass::where('code', $code);

        if ($propertyId !== null) {
            // Apply explict property check (can use withoutGlobalScopes if doing bulk overrides)
            $query->where('property_id', $propertyId);
        }

        return $query->exists();
    }
}
