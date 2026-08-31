<?php

namespace Gsc\Tanzpartnersuche\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\CacheStorage;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Zentrale Rate-Limiting-Konfiguration für die Tanzpartnersuche. Alle Limiter teilen sich einen
 * dateibasierten Cache unterhalb von var/cache, damit die Zähler den einzelnen PHP-Request und
 * einzelne PHP-FPM-Worker überdauern.
 */
class RateLimiterService implements SingletonInterface
{
    private readonly CacheStorage $storage;

    public function __construct()
    {
        $cache = new FilesystemAdapter(
            namespace: 'tanzpartnersuche_ratelimiter',
            directory: Environment::getVarPath() . '/cache/rate-limiter',
        );
        $this->storage = new CacheStorage($cache);
    }

    // Login: verhindert Credential-Stuffing/Brute-Force gegen loggedinAction().
    public function forLogin(string $key): LimiterInterface
    {
        return $this->factory('login', 5, '15 minutes')->create($key);
    }

    // Registrierung, pro Absender (IP): verhindert Mail-Bombing beliebiger Ziel-Adressen
    // und Massenanlage von Datensätzen über createAction().
    public function forRegistration(string $key): LimiterInterface
    {
        return $this->factory('registration', 5, '1 hour')->create($key);
    }

    // Registrierung, pro Ziel-E-Mail-Adresse: verhindert gezieltes Mail-Bombing eines
    // einzelnen Postfachs über createAction(), unabhängig von der Absender-IP.
    public function forRegistrationRecipient(string $key): LimiterInterface
    {
        return $this->factory('registration_recipient', 3, '1 hour')->create($key);
    }

    // Verifizierungscode-Eingabe: verhindert Durchprobieren gegen statusAction()
    // (defense-in-depth, der Code selbst ist bereits 128 Bit Zufall).
    public function forVerification(string $key): LimiterInterface
    {
        return $this->factory('verification', 10, '15 minutes')->create($key);
    }

    // Passwort-Reset-Anfrage: verhindert Mail-Flut/Missbrauch von sendresetAction().
    public function forPasswordReset(string $key): LimiterInterface
    {
        return $this->factory('password_reset', 5, '1 hour')->create($key);
    }

    // Kontaktformular, pro Absender (IP): verhindert automatisiertes Massen-Mailing über mailAction().
    public function forContactMail(string $key): LimiterInterface
    {
        return $this->factory('contact_mail', 10, '1 hour')->create($key);
    }

    // Kontaktformular, pro angeschriebenem Profil: verhindert das gezielte Zuspammen eines einzelnen Postfachs.
    public function forContactMailRecipient(string $key): LimiterInterface
    {
        return $this->factory('contact_mail_recipient', 5, '1 hour')->create($key);
    }

    // Lösch-Umfrage: verhindert Missbrauch von deletesurveysendAction() als Mail-Relay.
    public function forDeleteSurvey(string $key): LimiterInterface
    {
        return $this->factory('delete_survey', 10, '1 day')->create($key);
    }

    private function factory(string $id, int $limit, string $interval): RateLimiterFactory
    {
        return new RateLimiterFactory([
            'id' => $id,
            'policy' => 'sliding_window',
            'limit' => $limit,
            'interval' => $interval,
        ], $this->storage);
    }
}
