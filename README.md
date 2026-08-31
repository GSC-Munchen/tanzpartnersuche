# Tanzpartnersuche

A TYPO3 extension that provides a self-service "dance partner search" platform for club/community websites. Visitors can register a profile, browse and filter other members' profiles, and manage their own account — without any backend/administrator interaction required for day-to-day use.

> **Requirement:** This extension requires **TYPO3 v14** (`typo3/cms-core: ^14.3`) or later. 

## Features

- **Public registration & email verification** — users sign up with a profile (username, email, height, age, gender, dance role, category, level, bio) and confirm their account via a verification link.
- **Login / logout** with password-based authentication (hashed via TYPO3's `PasswordHashFactory`).
- **Search & browse** other members' profiles with filtering.
- **Self-service profile management** — edit profile data, change password, or delete the account entirely (including an optional deletion survey).
- **Password reset flow** via email with a time-limited token.
- **Rate limiting** on sensitive actions (e.g. login, registration, password reset) to mitigate abuse, built on `symfony/rate-limiter`.
- **Backend administration module** for editors/admins to view, edit, and delete member profiles, and trigger password resets, directly from the TYPO3 backend.
- Ships as a TYPO3 content element plugin, so it can be placed on any page via the page content wizard.

## Requirements

- TYPO3 **v14.3** or newer
- PHP version compatible with TYPO3 v14
- `symfony/rate-limiter` (`^6.4|^7.0|^8.0`)
- `symfony/cache` (`^6.4|^7.0|^8.0`)

## Installation

### Via Composer (recommended)

```bash
composer require gsc/tanzpartnersuche
```

Then activate the extension in the TYPO3 backend (**Admin Tools → Extensions**), or via CLI:

```bash
vendor/bin/typo3 extension:setup
```

### Setup after installation

1. Run the database schema update (**Admin Tools → Maintenance → Analyze Database Structure**, or `vendor/bin/typo3 database:updateschema`) to create the extension's database table.
2. Add a page where the plugin should appear, then insert the **"Tanzpartnersuche"** plugin via the content element wizard.
3. Add the **"Tanzpartnersuche"** backend module (found under the **Web** module group) to the backend user groups that should manage member profiles.
4. Configure mail sending (TYPO3's global mail settings) so that verification, password-reset, and notification emails can be delivered.
5. A scheduler task (`CleanupExpiredProfilesCommand`) is available to periodically remove expired/unverified profiles — set it up via **Admin Tools → Scheduler** if desired.

## License

This project is licensed under the [MIT License](LICENSE).
