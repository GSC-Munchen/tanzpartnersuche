<?php

namespace Gsc\Tanzpartnersuche\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Maskiert eine E-Mail-Adresse für die Anzeige im Backend-Modul, z.B.
 * "max.mustermann@example.com" -> "m***r@example.com".
 */
class MaskEmailViewHelper extends AbstractViewHelper {

    protected $escapeOutput = false;

    public function initializeArguments(): void {
        $this->registerArgument('email', 'string', 'Die zu maskierende E-Mail-Adresse', true);
    }

    public function render(): string {
        $email = (string)$this->arguments['email'];

        if (!str_contains($email, '@')) {
            return htmlspecialchars($email);
        }

        [$local, $domain] = explode('@', $email, 2);

        if (mb_strlen($local) <= 1) {
            $localMasked = $local . '***';
        } else {
            $localMasked = mb_substr($local, 0, 1) . '***' . mb_substr($local, -1);
        }

        return htmlspecialchars($localMasked . '@' . $domain);
    }
}
