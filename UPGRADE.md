# Upgrade Guide

## 2.x → 3.0 — Host-relative avatar paths

`users.avatar` is now stored as a **host-relative path** (e.g.
`/storage/3/photo.jpg`) instead of an absolute URL, so stored avatars stay valid
when the app's domain changes.

### Requirements

The model now calls `imagePathFromUrl()` / `imageUrlFromPath()` from
`App\Models\Traits\HasHashedMediaTrait`. Both ship with the matching Laravel
Starter release — make sure the host app is updated before/with this package.

### Data backfill

A migration rewrites existing absolute avatar URLs to relative paths:

```bash
php artisan migrate
```

(`database/migrations/..._backfill_users_avatar_relative_path.php`, shipped in
the Laravel Starter host app.)

### Breaking change: absolute-URL reads

The admin views are unaffected — every avatar is rendered via `asset($user->avatar)`
or an `<img src>`, both of which resolve a relative path correctly. **Only** code
that needs a fully-qualified avatar URL (JSON APIs, mail, og tags) must change:

```php
// before
$url = $user->avatar;

// after
$url = $user->avatar_full_url;
```

The new `avatar_full_url` accessor (on `App\Models\User`) returns the absolute
URL, expanding relative paths and passing legacy absolute rows through unchanged.

### Automated help

If `nasirkhan/module-manager` is installed, its upgrade command also scans for
`->avatar` reads that need attention:

```bash
php artisan module-manager:upgrade-media-paths --scan-only
```
